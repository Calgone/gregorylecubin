<?php

/**
 * Description of ReaderController
 *
 * @author greg
 */

namespace Greg\ReaderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Greg\ReaderBundle\Entity\Category;
use Greg\ReaderBundle\Entity\Sub;

class ReaderController extends Controller {

    public function indexAction() {
        // On affiche touts les abonnements (subs) de chaque catégorie
        $categories = $this->getDoctrine()
                ->getManager()
                ->getRepository('GregReaderBundle:Category')
                ->getCategories();

        return $this->render('GregReaderBundle:Reader:index.html.twig', array(
                    'categories' => $categories
        ));

//    $parser = $this->get('rss_parser');
//    $retourParser = $parser->parser("http://www.lefigaro.fr/rss/figaro_une.xml", 5, false);
//    return $this->render('GregReaderBundle:Reader:index.html.twig', array(
//                    'parser'  => $retourParser,
//        ));
        //return new Response("Hello vous êtes sur le reader.");
    }
    
    public function voirSubAction(Sub $sub)
    {
        $parser = $this->get('rss_parser');
        $retourParser = $parser->parser($sub->getXmlUrl(), 5, false);
        return $this->render('GregReaderBundle:Reader:sub.html.twig', array(
            'feeds'    => $retourParser,
        ));
    }

}

?>
