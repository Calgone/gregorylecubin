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
     * @ORM\OneToMany(targetEntity="Greg\ReaderBundle\Entity\Sub", mappedBy="category") 
     */
    private $subs;

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
        $this->subs = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add subs
     *
     * @param \Greg\ReaderBundle\Entity\Sub $subs
     * @return Category
     */
    public function addSub(\Greg\ReaderBundle\Entity\Sub $subs)
    {
        $this->subs[] = $subs;
    
        return $this;
    }

    /**
     * Remove subs
     *
     * @param \Greg\ReaderBundle\Entity\Sub $subs
     */
    public function removeSub(\Greg\ReaderBundle\Entity\Sub $subs)
    {
        $this->subs->removeElement($subs);
    }

    /**
     * Get subs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSubs()
    {
        return $this->subs;
    }
}