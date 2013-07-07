<?php

/**
 * Description of RssParser
 *
 * @author greg
 */

namespace Greg\ReaderBundle\Rss;

//use Greg\ReaderBundle\Entity\Channel;
use Greg\ReaderBundle\Entity\Item;
use Doctrine\ORM\EntityManager;

class RssParser {

    protected $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function parser($nb = 5) {
        $str = '';
        $i = 0;

        $channels = $this->em->getRepository('GregReaderBundle:Channel')
                ->getChannels();
//        return(var_dump($channels));
        foreach ($channels as $channel) {
            $i = 0;
            $rss = simplexml_load_file($channel->getXmlUrl());

            if ($rss) {
                foreach ($rss->channel->item as $rssItem) {
                    
                    $cat = '';
                    
                    $item = new Item();
                    $item->setTitle($rssItem->title);
                    $pubd = new \DateTime($rssItem->pubDate);
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

                    $i++;
                    if ($i == $nb) {
                        break;
                    }
                }
                $this->em->flush();
            } else {
                $str .= "Impossible de charger le flux ".$channel->getXmlUrl();
            }
        }
        return $str . "\nTerminé !!";
    }
}
?>
