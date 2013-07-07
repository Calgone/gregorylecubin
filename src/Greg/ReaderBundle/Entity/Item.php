<?php

namespace Greg\ReaderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Feed
 *
 * @ORM\Table(name="reader_item")
 * @ORM\Entity(repositoryClass="Greg\ReaderBundle\Entity\FeedRepository")
 */
class Item {

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
     * @ORM\Column(name="link", type="string", length=255)
     */
    private $link;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     * 
     * @ORM\Column(name="author", type="string", length=255)
     */
    private $author;
    
    /**
     * @var category
     * 
     * @ORM\Column(name="category", type="string", length=255)
     * 
     */
    private $category;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="comments", type="string", length=255)
     * 
     */
    private $comments;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="enclosure", type="string", length=255) 
     */
    private $enclosure;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="guid", type="string", length=255)
     */
    private $guid;
    
    /**
     * @var datetime
     * 
     * @ORM\Column(name="pubdate", type="datetime", nullable=true)
     */
    private $pubDate;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="source", type="string", length=255)
     */
    private $source;

    /**
     * @var string
     *
     * @ORM\Column(name="language", type="string", length=50)
     */
    private $language;

    /**
     * @var integer
     * 
     * @ORM\ManyToOne(targetEntity="Greg\ReaderBundle\Entity\Channel", inversedBy="items")
     */
    private $channel;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Item
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Item
     */
    public function setUrl($url) {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * Set link
     *
     * @param string $link
     * @return Item
     */
    public function setLink($link) {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string 
     */
    public function getLink() {
        return $this->link;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Item
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set language
     *
     * @param string $language
     * @return Item
     */
    public function setLanguage($language) {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return string 
     */
    public function getLanguage() {
        return $this->language;
    }

    /**
     * Set channel
     *
     * @param \Greg\ReaderBundle\Entity\Channel $channel
     * @return Item
     */
    public function setChannel(\Greg\ReaderBundle\Entity\Channel $channel = null) {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Get channel
     *
     * @return \Greg\ReaderBundle\Channel 
     */
    public function getChannel() {
        return $this->channel;
    }


    /**
     * Set author
     *
     * @param string $author
     * @return Item
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    
        return $this;
    }

    /**
     * Get author
     *
     * @return string 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set category
     *
     * @param string $category
     * @return Item
     */
    public function setCategory($category)
    {
        $this->category = $category;
    
        return $this;
    }

    /**
     * Get category
     *
     * @return string 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set comments
     *
     * @param string $comments
     * @return Item
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    
        return $this;
    }

    /**
     * Get comments
     *
     * @return string 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set enclosure
     *
     * @param string $enclosure
     * @return Item
     */
    public function setEnclosure($enclosure)
    {
        $this->enclosure = $enclosure;
    
        return $this;
    }

    /**
     * Get enclosure
     *
     * @return string 
     */
    public function getEnclosure()
    {
        return $this->enclosure;
    }

    /**
     * Set guid
     *
     * @param string $guid
     * @return Item
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;
    
        return $this;
    }

    /**
     * Get guid
     *
     * @return string 
     */
    public function getGuid()
    {
        return $this->guid;
    }

    /**
     * Set pubDate
     *
     * @param \DateTime $pubDate
     * @return Item
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
     * Set source
     *
     * @param string $source
     * @return Item
     */
    public function setSource($source)
    {
        $this->source = $source;
    
        return $this;
    }

    /**
     * Get source
     *
     * @return string 
     */
    public function getSource()
    {
        return $this->source;
    }
}