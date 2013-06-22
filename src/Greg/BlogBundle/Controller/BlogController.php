<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BlogController
 *
 * @author greg
 */

namespace Greg\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends Controller {

    public function indexAction() {
        $id = 4;
//        return new Response("Hello World !!!");
//        return $this->render('GregBlogBundle:Blog:accueil.html.twig', array('nom' => 'Grégou'));
        $url = $this->generateUrl('blog_voir', array('id' => $id), true);
        return $this->redirect($url);
    }
    
    public function voirAction($id) {
        return new Response("Affichage de l'article : $id");
    }
    
    public function voirSlugAction($slug, $annee, $format)
    {
        return new Response("Voici l'article $slug pour l'année $annee au format $format");
    }
}

?>
