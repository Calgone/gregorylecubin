<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RssParser
 *
 * @author greg
 */

namespace Greg\ReaderBundle\Rss;

class RssParser {
    
    protected $url;
    protected $description;
    protected $language;
    protected $link;
    protected $title;
    
    public function __construct(){}
    
    public function parser($url, $nb = null, $tag = null)
    {
        $this->url = $url;
        
        $rss = simplexml_load_file($this->url);
        
        if ($rss)
        {
            if (!empty($rss->channel->language))
            {
                $this->language = (string) $rss->channel->language;
            }
            if (!empty($rss->channel->title))
            {
                $this->title = (string) $rss->channel->title;
            }
            if (!empty($rss->channel->description))
            {
                $this->description = (string) $rss->channel->description;
            }
            if (!empty($rss->channel->link))
            {
                $this->link = $rss->channel->link;
            }
            
            $i = 0;
            
            foreach ($rss->channel->item as $item)
            {
                $itemTitle = (string) $item->title;
                $itemPubDate = (string) $item->pubDate;
                $itemLink = (string) $item->link;
                $itemDescription = (string) $item->description;
                $itemCategory = array();
                
                if ($tag == false)
                    $itemDescription = strip_tags ($itemDescription);
                
                $y = 0;
                
                foreach ($item->category as $cat)
                {
                    $itemCategory[$y] = (string) $cat;
                    $y++;
                }
                $retourParser[$i] = array(
                                'title'         => $itemTitle,
                                'pubDate'       => $itemPubDate,
                                'description'   => $itemDescription,
                                'link'          => $itemLink,
                                'category'      => $itemCategory);
                $i++;
                
                if (count($retourParser) == $nb)
                {
                    break;
                }
            }
            return $retourParser;
        } else {
            return "Impossible de charger le flux $url"; 
        }
    }
    
    public function getTitle()
    {
        return $this->title;
    }
    
    public function getLink()
    {
        return $this->link;
    }
    
    public function getDescription()
    {
        return $this->description;
    }
    
    public function getLanguage()
    {
        return $this->language;
    }
    
    public function getUrl()
    {
        return $this->url;
    }
}

?>
