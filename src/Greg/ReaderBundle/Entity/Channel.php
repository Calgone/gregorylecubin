<?php

namespace Greg\ReaderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Subs
 *
 * @ORM\Table(name="reader_channel")
 * @ORM\Entity(repositoryClass="Greg\ReaderBundle\Entity\ChannelRepository")
 */
class Channel
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=50)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="xml_url", type="string", length=255)
     */
    private $xmlUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="html_url", type="string", length=255)
     */
    private $htmlUrl;

    /**
     * @ORM\Column(name="description", type="text")
     */
    private $description;
    
    /**
     * @ORM\Column(name="language", type="string", length=20)
     */
    private $language;
    
    /**
     * @ORM\Column(name="pubdate", type="datetime")
     */
    private $pubDate;
    
    /**
     * @ORM\Column(name="lastBuildDate", type="datetime")
     */
    private $lastBuildDate;
    
    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Greg\ReaderBundle\Entity\Category", inversedBy="channels")
     */
    private $category;
    
    /**
     * @var integer
     * 
     * @ORM\OneToMany(targetEntity="Greg\ReaderBundle\Entity\Item", mappedBy="channel")
     */
    private  $items;
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Subs
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Subs
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set xmlUrl
     *
     * @param string $xmlUrl
     * @return Subs
     */
    public function setXmlUrl($xmlUrl)
    {
        $this->xmlUrl = $xmlUrl;
    
        return $this;
    }

    /**
     * Get xmlUrl
     *
     * @return string 
     */
    public function getXmlUrl()
    {
        return $this->xmlUrl;
    }

    /**
     * Set htmlUrl
     *
     * @param string $htmlUrl
     * @return Subs
     */
    public function setHtmlUrl($htmlUrl)
    {
        $this->htmlUrl = $htmlUrl;
    
        return $this;
    }

    /**
     * Get htmlUrl
     *
     * @return string 
     */
    public function getHtmlUrl()
    {
        return $this->htmlUrl;
    }

    /**
     * Set category
     *
     * @param integer $category
     * @return Subs
     */
    public function setCategory($category)
    {
        $this->category = $category;
    
        return $this;
    }

    /**
     * Get category
     *
     * @return integer 
     */
    public function getCategory()
    {
        return $this->category;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add items
     *
     * @param \Greg\ReaderBundle\Entity\Item $items
     * @return Channel
     */
    public function addItem(\Greg\ReaderBundle\Entity\Item $items)
    {
        $this->items[] = $items;
    
        return $this;
    }

    /**
     * Remove items
     *
     * @param \Greg\ReaderBundle\Entity\Item $items
     */
    public function removeItem(\Greg\ReaderBundle\Entity\Item $items)
    {
        $this->items->removeElement($items);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Channel
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set language
     *
     * @param string $language
     * @return Channel
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    
        return $this;
    }

    /**
     * Get language
     *
     * @return string 
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set pubDate
     *
     * @param \DateTime $pubDate
     * @return Channel
     */
    public function setPubDate($pubDate)
    {
        $this->pubDate = $pubDate;
    
        return $this;
    }

    /**
     * Get pubDate
     *
     * @return \DateTime 
     */
    public function getPubDate()
    {
        return $this->pubDate;
    }

    /**
     * Set lastBuildDate
     *
     * @param \DateTime $lastBuildDate
     * @return Channel
     */
    public function setLastBuildDate($lastBuildDate)
    {
        $this->lastBuildDate = $lastBuildDate;
    
        return $this;
    }

    /**
     * Get lastBuildDate
     *
     * @return \DateTime 
     */
    public function getLastBuildDate()
    {
        return $this->lastBuildDate;
    }
}