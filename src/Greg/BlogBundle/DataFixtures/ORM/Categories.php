<?php

namespace Greg\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Greg\BlogBundle\Entity\Categorie;

/**
 * Description of Categorie
 *
 * @author greg
 */
class Categories implements FixtureInterface 
{
    //dans l'argument de la méthode load, l'objet $manager
    // est l'EntityManager
    public function load(ObjectManager $manager)
    {
        //liste des noms de catégorie à ajouter
        $noms = array('Symfony2', 'Doctrine2', 'Tutoriel', 'Évènement');
        
        foreach ($noms as $i => $nom)
        {
            // On crée la catégorie
            $liste_categories[$i] = new Categorie();
            $liste_categories[$i]->setNom($nom);
            // On la persiste
            $manager->persist($liste_categories[$i]);
        }
        // On déclenche l'enregistrement 
        $manager->flush();
    }
}

?>
