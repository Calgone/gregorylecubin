<?php
/**
 * Description of Subs
 *
 * @author greg
 */

namespace Greg\ReaderBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Greg\ReaderBundle\Entity\Sub;

class Subs extends AbstractFixture implements OrderedFixtureInterface {
    public function load(ObjectManager $manager)
    {
        $i = 0;
        $sub = new Sub();
        $sub->setCategory($manager->merge($this->getReference('cat' . $i)));
        $sub->setXmlUrl('http://www.lefigaro.fr/rss/figaro_une.xml');
        $sub->setHtmlUrl('http://www.lefigaro.fr');
        $sub->setTitle('Le Figaro');
        $sub->setType('rss');
        $manager->persist($sub);
        
        $i = 1;
        $sub = new Sub();
        $sub->setCategory($manager->merge($this->getReference('cat' . $i)));
        $sub->setXmlUrl('http://planet-libre.org/rss10.php');
        $sub->setHtmlUrl('http://planet-libre.org');
        $sub->setTitle('Planet-Libre');
        $sub->setType('rss');
        $manager->persist($sub);
        
        $i = 2;
        $sub = new Sub();
        $sub->setCategory($manager->merge($this->getReference('cat' . $i)));
        $sub->setXmlUrl('http://rss.slashdot.org/Slashdot/slashdot');
        $sub->setHtmlUrl('http://slashdot.org');
        $sub->setTitle('Slashdot');
        $sub->setType('rss');
        $manager->persist($sub);
        
        // On déclenche l'enregistrement 
        $manager->flush();
        
    }
    
    public function getOrder()
    {
        return 2;
    }
}

?>
