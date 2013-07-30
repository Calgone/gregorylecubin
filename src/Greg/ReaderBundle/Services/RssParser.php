<?php

/**
 * Description of RssParser
 *
 * @author greg
 */

namespace Greg\ReaderBundle\Services;

use Greg\ReaderBundle\Entity\Channel;
use Greg\ReaderBundle\Entity\Item;
use Doctrine\ORM\EntityManager;

class RssParser {

    protected $em;

//    protected $fi;

    public function __construct(EntityManager $em, FaviconFetcher $fi) {
        $this->em = $em;
//        $this->fi = $fi;
    }

    public function parser($nb = 5) {
        $str = '';
        $i = 0;
        $cpt = 0; //nbre total d'items ajoutés
        $foundIcon = false;

        $channels = $this->em->getRepository('GregReaderBundle:Channel')
                ->getChannels();
//        return(var_dump($channels));
        foreach ($channels as $channel) {
            $lastItem = $this->em->getRepository('GregReaderBundle:Item')
                    ->getLastItem($channel);
            if ($lastItem) {
                $lastItemDate = $lastItem->getPubDate();
            }

            $i = 0;
            $rss = simplexml_load_file($channel->getXmlUrl());

            if ($rss) {
                //sauvegarde du channel
//                $this->fi->fetchUrl($channel->getHtmlUrl());
                $foundIcon = $this->getFavicon($channel->getHtmlUrl());
                
                if (!$foundIcon) {
                    throw new \Exception('Erreur icone non trouvée', 0);
                }

                $channel->setFaviconUrl($foundIcon);
                var_dump($foundIcon);
//               
                $filename = $this->sanitize_filename($rss->channel->title);
                $fileparts = pathinfo($foundIcon);
                $fileext = $fileparts['extension'];
                $filename .= '.' . $fileext;
                
                $channel->setFaviconFilename($filename);
                $channel->setDescription($rss->channel->description);
                $channel->setLanguage($rss->channel->language);
                $this->em->persist($channel);

                //sauvegarde des items
                foreach ($rss->channel->item as $rssItem) {

                    $cat = '';

                    $pubd = new \DateTime($rssItem->pubDate);
                    $tz = new \DateTimeZone('Europe/Paris');
                    $pubd->setTimezone($tz);

                    if ((!$lastItem) or ($pubd > $lastItemDate)) {
                        $item = new Item();
                        $item->setTitle($rssItem->title);

                        $item->setPubDate($pubd);
                        $item->setLink($rssItem->link);
                        $item->setDescription($rssItem->description);
                        $item->setAuthor($rssItem->author);
                        $item->setComments($rssItem->comments);
                        $item->setEnclosure($rssItem->enclosure);
                        $item->setGuid($rssItem->guid);
                        $item->setSource($rssItem->source);
                        $item->setLanguage($rssItem->language);
                        $item->setChannel($channel);

                        foreach ($rssItem->category as $cat) {
                            $cat .= $cat . " ";
                        }
                        $item->setCategory(trim($cat));

                        $this->em->persist($item);
                        $cpt++;
                    }
                    $i++;

                    if ($i == $nb) {
                        break;
                    }
                }
                $this->em->flush();
            } else {
                $str .= "Impossible de charger le flux "
                        . $channel->getXmlUrl();
            }
        }
        return $str . "\nParsing terminé : " . $cpt . " items ajoutés \n";
    }

    private function sanitize_filename($string) {
        // Remove special accented characters - ie. sí.
        $clean_name = strtr($string, 'ŠŽšžŸÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜÝàáâãäåçèéêëìíîïñòóôõöøùúûüýÿ', 'SZszYAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy');
        $clean_name = strtr($clean_name, array('Þ' => 'TH', 'þ' => 'th', 'Ð' => 'DH', 'ð' => 'dh', 'ß' => 'ss', 'Œ' => 'OE', 'œ' => 'oe', 'Æ' => 'AE', 'æ' => 'ae', 'µ' => 'u'));
        $clean_name = strtolower($clean_name);
        $clean_name = substr($clean_name, 0, 50);
        $clean_name = preg_replace(array('/\s/', '/\.[\.]+/', '/[^\w_\.\-]/'), array('_', '.', ''), $clean_name);
        return $clean_name;
    }

    private function getFavicon($url) {

// "validate" URL
        $urlParts = parse_url($url);
        if (!$urlParts) {
            throw new \Exception('Unable to parse URL');
        }
        if (!isset($urlParts['scheme'])) {
            $urlParts['scheme'] = '';
        }
        $urlParts['scheme'] = strtolower($urlParts['scheme']);
        if ($urlParts['scheme'] !== 'http' && $urlParts['scheme'] !== 'https') {
            throw new \Exception('Non-HTTP URL');
        }

        $foundIcon = false;

// set up base URL
        $remoteBaseUrl = $urlParts['scheme'] . '://';
        if (isset($urlParts['user'])) {
            $remoteBaseUrl .= $urlParts['user'];
            if (isset($urlParts['pass'])) {
                $remoteBaseUrl .= ':' . $urlParts['pass'];
            }
            $remoteBaseUrl .= '@';
        }
        $remoteBaseUrl .= $urlParts['host'];
        if (isset($urlParts['port'])) {
            $remoteBaseUrl .= ':' . $urlParts['port'];
        }

// check root; do this first because it's preferred by browsers
        if ($fp = @fopen($remoteBaseUrl . '/favicon.ico', 'r')) {
            $foundIcon = $remoteBaseUrl . '/favicon.ico';
            fclose($fp);
        }

// if that didn't work, we need to parse the passed URL's contents
        if (!$foundIcon) {
            $dom = new \DOMDocument;
            libxml_use_internal_errors(true); // suppress errors
            $dom->preserveWhiteSpace = false;
            $dom->recover = true;
            $dom->strictErrorChecking = false;
            if (!@$dom->loadHTMLFile($url)) {
                throw new \Exception('Failed to load URL');
            }

            $xpath = new \DOMXpath($dom);
        }

        if (!$foundIcon) {
// try <link rel="shortcut icon" href="/icon.png" />
            $q = $xpath->query('//link[@rel="shortcut icon"]/@href');
            if ($q->length) {
                $foundIcon = $q->item(0)->value;
            }
        }

        if (!$foundIcon) {
// try <link rel="icon" type="image/png" href="/icon.png" />
            $q = $xpath->query('//link[@rel="icon"]/@href');
            if ($q->length) {
                $foundIcon = $q->item(0)->value;
            }
        }

        if (!$foundIcon) {
            throw new \Exception("Could not determine icon from URL's content");
        }

// we have an icon; ensure that it's absolute, not local or relative
        if (strpos($foundIcon, '//') === 0) {
// schemaless URL; give it a schema:
            $foundIcon = $urlParts['sheme'] . ':' . $foundIcon;
        } else {
// does not start with //
            $parsedIcon = parse_url($foundIcon);
            if (!isset($parsedIcon['scheme'])) {
// if the URL already contains a scheme, it's already absolute
// check to see if it's a local path (vs. relative)
                if ('/' === $foundIcon[0]) {
// just add the remote base URL
                    $foundIcon = $remoteBaseUrl . $foundIcon;
                } else {
// does not start with /, so it must be relative
// this can get complicated
// assume that paths containing '.' in the last part are files
                    $pathParts = explode('/', $urlParts['path']);
                    if (strpos($pathParts[count($pathParts) - 1], '.') !== false) {
// path points to a file
                        array_pop($pathParts); // discard
                    }
// reconstruct
                    $recIcon = $remoteBaseUrl . '/' . implode('/', $pathParts);
                    if ($pathParts) {
                        $recIcon .= '/'; // avoid double /
                    }
                    $recIcon .= $foundIcon;
                    $foundIcon = $recIcon;
                }
            }
        }

        return $foundIcon;
    }

}

?>
