<?php

namespace Greg\ReaderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Categorie
 *
 * @ORM\Table(name="reader_category")
 * @ORM\Entity(repositoryClass="Greg\ReaderBundle\Entity\CategoryRepository")
 */
class Category
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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $name;


    /**
     *@var integer
     * 
     * @ORM\OneToMany(targetEntity="Greg\ReaderBundle\Entity\Channel", mappedBy="category") 
     */
    private $channels;

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
     * Set name
     *
     * @param string $name
     * @return Categorie
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->channels = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add channels
     *
     * @param \Greg\ReaderBundle\Entity\Channel $channels
     * @return Category
     */
    public function addChannel(\Greg\ReaderBundle\Entity\Channel $channels)
    {
        $this->channels[] = $channels;
    
        return $this;
    }

    /**
     * Remove channels
     *
     * @param \Greg\ReaderBundle\Entity\Channel $channels
     */
    public function removeChannel(\Greg\ReaderBundle\Entity\Channel $channels)
    {
        $this->channels->removeElement($channels);
    }

    /**
     * Get channels
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChannels()
    {
        return $this->channels;
    }
}