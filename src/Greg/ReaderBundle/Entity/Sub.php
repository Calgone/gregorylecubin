<?php

namespace Greg\ReaderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Subs
 *
 * @ORM\Table(name="reader_sub")
 * @ORM\Entity(repositoryClass="Greg\ReaderBundle\Entity\SubRepository")
 */
class Sub
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
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Greg\ReaderBundle\Entity\Category", inversedBy="subs")
     */
    private $category;
    
    /**
     * @var integer
     * 
     * @ORM\OneToMany(targetEntity="Greg\ReaderBundle\Entity\Feed", mappedBy="sub")
     */
    private  $feeds;
    
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
        $this->feeds = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add feeds
     *
     * @param \Greg\ReaderBundle\Entity\Feed $feeds
     * @return Sub
     */
    public function addFeed(\Greg\ReaderBundle\Entity\Feed $feeds)
    {
        $this->feeds[] = $feeds;
    
        return $this;
    }

    /**
     * Remove feeds
     *
     * @param \Greg\ReaderBundle\Entity\Feed $feeds
     */
    public function removeFeed(\Greg\ReaderBundle\Entity\Feed $feeds)
    {
        $this->feeds->removeElement($feeds);
    }

    /**
     * Get feeds
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFeeds()
    {
        return $this->feeds;
    }
}