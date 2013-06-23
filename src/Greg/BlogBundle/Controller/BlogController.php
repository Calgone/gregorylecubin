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

    public function indexAction($page) {
        if ($page < 1) {
            throw $this->createNotFoundException("Page inexistante (page = $page)");
        }
        //récupérer la liste des articles
        $articles = array(
            array(
                'titre' => 'Mon weekend a Phi Phi Island !',
                'id' => 1,
                'auteur' => 'winzou',
                'contenu' => 'Ce weekend était trop bien. Blabla…',
                'date' => new \Datetime()),
            array(
                'titre' => 'Repetition du National Day de Singapour',
                'id' => 2,
                'auteur' => 'winzou',
                'contenu' => 'Bientôt prêt pour le jour J. Blabla…',
                'date' => new \Datetime()),
            array(
                'titre' => 'Chiffre d\'affaire en hausse',
                'id' => 3,
                'auteur' => 'M@teo21',
                'contenu' => '+500% sur 1 an, fabuleux. Blabla…',
                'date' => new \Datetime())
        );
        return $this->render('GregBlogBundle:Blog:index.html.twig', array(
                    'articles' => $articles
        ));
    }

    public function menuAction($nombre) {
        //liste du menu
        $liste = array(
            array('id' => 7, 'titre' => 'Post 7'),
            array('id' => 3, 'titre' => 'Post 3'),
            array('id' => 4, 'titre' => 'Post 4')
        );
        return $this->render('GregBlogBundle:Blog:menu.html.twig', array(
                    'liste_articles' => $liste
        ));
    }

    public function voirAction($id) {
        //on récupère le repository
        $repository = $this->getDoctrine()
                            ->getManager()
                            ->getRepository('GregBlogBundle:Article');
        
        //on récupère l'entité correspondant à l'id $id
        $article = $repository->find($id);
        
        // $article est donc une instance de Greg\BlogBundle\Entity\Article
        // ou null si aucun article trouvé avec cet id
        if ($article === null)
        {
            throw $this->createNotFoundException("Article[id=$id] inexistant");
        }
        
        return $this->render('GregBlogBundle:Blog:voir.html.twig', array(
                    'article' => $article
        ));
    }

    public function ajouterAction() {
        $titre = "Mes dernières vacances";
        $auteur = "Greg";
        $contenu = "C'était vraiment très bien...";
        
        //création de l'entité
        $article = new \Greg\BlogBundle\Entity\Article;
        $article->setTitre($titre);
        $article->setAuteur($auteur);
        $article->setContenu($contenu);
        
        //création de l'entité Image
        $image = new \Greg\BlogBundle\Entity\Image;
        $image->setUrl('http://uploads.siteduzero.com/icones/478001_479000/478657.png');
        $image->setAlt('Logo Symfony2');
        
        //on lie l'image à l'article
        $article->setImage($image);
        
        //on récupère l'entity manager
        $em = $this->getDoctrine()->getManager();
        // 1 : on persiste l'entité
        $em->persist($article);
        // 2 : on flush tout ce qui a été persisté avant
        $em->flush();
//          traitement du POST et vérification (service antispam)
        if ($this->get('request')->getMethod() == 'POST') {
            $this->get('session')->getFlashBag()->add('notice', 'Article bien enregistré');
            //on redirige vers la page de visualisation de cet article
            return $this->redirect($this->generateUrl('blog_voir', array('id' => $article->getId())) );
        }
        
        $antispam = $this->container->get('greg_blog.antispam');
//        $contenu = "blahblahhttp://www.greg.com http://www.toto.com http://www.test.com";
        
        if ($antispam->isSpam($contenu))
        {
            throw new \Exception('Votre message a été détecté comme spam !');
        }
        // si on est pas en POST, on affiche le formulaire:
        return $this->render('GregBlogBundle:Blog:ajouter.html.twig');
        
    }

    public function modifierAction($id) {
        //on récupère l'article correspondant à id
        //on gère la création et la gestion du formulaire
        $article = array(
            'id' => 1,
            'titre' => 'Mon weekend a Phi Phi Island !',
            'auteur' => 'winzou',
            'contenu' => 'Ce weekend était trop bien. Blabla…',
            'date' => new \Datetime()
        );
        return $this->render('GregBlogBundle:Blog:modifier.html.twig', array(
                'article' => $article
        ));
    }

    public function supprimerAction($id) {
        //on supprime l'article correspondant à id

        return $this->render('GregBlogBundle:Blog:supprimer.html.twig');
    }

    public function mailerAction() {
        $mailer = $this->get('mailer');
        $message = \Swift_Message::newInstance()
                ->setSubject('Hello greg')
                ->setFrom('tuto@chezmoi.com')
                ->setTo('gregory.lecubin@gmail.com')
                ->setBody('Coucou, voici un email envoyé depuis mon site');
        $mailer->send($message);
        return new Response('Email envoyé !');
    }

}

?>
