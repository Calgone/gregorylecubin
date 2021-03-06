<?php

namespace Greg\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Article
 *
 * @ORM\Table(name="blog_article")
 * @ORM\Entity(repositoryClass="Greg\BlogBundle\Entity\ArticleRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Article
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="auteur", type="string", length=255)
     */
    private $auteur;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="text")
     */
    private $contenu;

    /**
     * @ORM\Column(name="publication", type="boolean") 
     */
    private $publication;
    
    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="date_edition", type="datetime", nullable=true)
     */
    private $dateEdition;
    
    /**
     * @ORM\OneToOne(targetEntity="Greg\BlogBundle\Entity\Image", cascade={"persist"})
     */
    private $image;
    
    /**
     * @ORM\ManyToMany(targetEntity="Greg\BlogBundle\Entity\Categorie", cascade={"persist"}) 
     */
    private $categories;
    
    /**
     * @ORM\OneToMany(targetEntity="Greg\BlogBundle\Entity\Commentaire", mappedBy="article")
     */
    private $commentaires;
    
    public function __construct() {
        $this->date = new \DateTime();
        $this->publication = true;
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
        $this->commentaires = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
        $this->setDateEdition(new \DateTime());
    }

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
     * Set date
     *
     * @param \DateTime $date
     * @return Article
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set titre
     *
     * @param string $titre
     * @return Article
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    
        return $this;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set auteur
     *
     * @param string $auteur
     * @return Article
     */
    public function setAuteur($auteur)
    {
        $this->auteur = $auteur;
    
        return $this;
    }

    /**
     * Get auteur
     *
     * @return string 
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * Set contenu
     *
     * @param string $contenu
     * @return Article
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;
    
        return $this;
    }

    /**
     * Get contenu
     *
     * @return string 
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set publication
     *
     * @param boolean $publication
     * @return Article
     */
    public function setPublication($publication)
    {
        $this->publication = $publication;
    
        return $this;
    }

    /**
     * Get publication
     *
     * @return boolean 
     */
    public function getPublication()
    {
        return $this->publication;
    }

    /**
     * Set image
     *
     * @param \Greg\BlogBundle\Entity\Image $image
     * @return Article
     */
    public function setImage(\Greg\BlogBundle\Entity\Image $image = null)
    {
        $this->image = $image;
    
        return $this;
    }

    /**
     * Get image
     *
     * @return \Greg\BlogBundle\Entity\Image 
     */
    public function getImage()
    {
        return $this->image;
    }

    

    /**
     * Add categories
     *
     * @param \Greg\BlogBundle\Entity\Categorie $categories
     * @return Article
     */
    public function addCategorie(\Greg\BlogBundle\Entity\Categorie $categorie)
    {
        $this->categories[] = $categorie;
    
        return $this;
    }

    /**
     * Remove categories
     *
     * @param \Greg\BlogBundle\Entity\Categorie $categories
     */
    public function removeCategorie(\Greg\BlogBundle\Entity\Categorie $categorie)
    {
        $this->categories->removeElement($categorie);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set dateEdition
     *
     * @param \DateTime $dateEdition
     * @return Article
     */
    public function setDateEdition($dateEdition)
    {
        $this->dateEdition = $dateEdition;
    
        return $this;
    }

    /**
     * Get dateEdition
     *
     * @return \DateTime 
     */
    public function getDateEdition()
    {
        return $this->dateEdition;
    }

    /**
     * Add commentaires
     *
     * @param \Greg\BlogBunlde\Entity\Commentaire $commentaires
     * @return Article
     */
    public function addCommentaire(\Greg\BlogBunlde\Entity\Commentaire $commentaires)
    {
        $this->commentaires[] = $commentaires;
        $commentaires->setArticle($this);
        return $this;
    }

    /**
     * Remove commentaires
     *
     * @param \Greg\BlogBunlde\Entity\Commentaire $commentaires
     */
    public function removeCommentaire(\Greg\BlogBunlde\Entity\Commentaire $commentaires)
    {
        $this->commentaires->removeElement($commentaires);
        // Si la relation était facultative (nullable=true, ce qui n'est pas le cas ici attention) :       
        // $commentaire->setArticle(null);
    }

    /**
     * Get commentaires
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCommentaires()
    {
        return $this->commentaires;
    }
}