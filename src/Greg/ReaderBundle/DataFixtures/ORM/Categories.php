<?php
/**
 * Description of Categorie
 *
 * @author greg
 */

namespace Greg\ReaderBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Greg\ReaderBundle\Entity\Category;

class Categories extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        //dans l'argument de la méthode load, l'objet $manager est l'EntityManager
        
        //liste des noms de catégorie à ajouter 
        $noms = array('News', 'Linux', 'Tech News');
        
        foreach ($noms as $i => $nom)
        {
            // On crée la catégorie
            $liste_categories[$i] = new Category();
            $liste_categories[$i]->setName($nom);
            // On la persiste
            $manager->persist($liste_categories[$i]);
            $this->addReference('cat' . $i, $liste_categories[$i]);
        }
        // On déclenche l'enregistrement 
        $manager->flush();
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }
}

?>
