<?php
/**
 * Description of BlogController
 *
 * @author greg
 */

namespace Greg\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Greg\BlogBundle\Entity\Article;
use Greg\BlogBundle\Entity\Image;
use Greg\BlogBundle\Entity\Commentaire;

class BlogController extends Controller {

    public function indexAction($page) {
        if ($page < 1) {
            throw $this->createNotFoundException("Page inexistante (page = $page)");
        }
        //récupérer la liste des articles
        
        $articles = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('GregBlogBundle:Article')
                        ->getArticles(3, $page);
        
        return $this->render('GregBlogBundle:Blog:index.html.twig', array(
                    'articles'  => $articles,
                    'page'      => $page,
                    'nbParPage' => ceil(count($articles) / 3)
        ));
    }

    public function menuAction($nombre) {
        //liste du menu
        $liste = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('GregBlogBundle:Article')
                        ->findBy(
                                array(),
                                array('date' => 'desc'),
                                $nombre,
                                0);
        
        return $this->render('GregBlogBundle:Blog:menu.html.twig', array(
                    'liste_articles' => $liste
        ));
    }

    public function voirAction(Article $article) {
        //on récupère le repository
//        $em = $this->getDoctrine()
//                ->getManager();
//        
//        $article = $em->getRepository('GregBlogBundle:Article')
//                    ->find($id);
        
        // $article est donc une instance de Greg\BlogBundle\Entity\Article
        // ou null si aucun article trouvé avec cet id
//        if ($article === null) {
//            throw $this->createNotFoundException("Article[id=$id] inexistant");
//        }
        // On récupère la liste des commentaires (si relation unidirectionnelle)
        //$liste_commentaires = $em->getRepository('GregBlogBundle:Commentaire')
        //                        ->findBy(array('article' => $id));
        //comme on a une relation bidirectionnelle, on twig peut accéder à {{ article.commentaires }}
        return $this->render('GregBlogBundle:Blog:voir.html.twig', array(
                    'article' => $article,
                    //'liste_commentaires' => $liste_commentaires
        ));
    }

    public function ajouterAction() {
        
        $article = new Article();
        
        // On crée le FormBuilder grâce à la méthode du contrôleur
        $formBuilder = $this->createFormBuilder($article);
        $formBuilder
                ->add('date',       'date')
                ->add('titre',      'text')
                ->add('contenu',    'textarea')
                ->add('auteur',     'text')
                ->add('publication','checkbox', array('required' => false));
        
        $form = $formBuilder->getForm();
        
//        $titre = "Mes dernières vacances";
//        $auteur = "Greg";
        $contenu = "C'était vraiment très bien...";
/*
        //création de l'entité
        $article = new Article;
        $article->setTitre($titre);
        $article->setAuteur($auteur);
        $article->setContenu($contenu);

        //création d'un premier commentaire
        $commentaire1 = new Commentaire;
        $commentaire1->setAuteur('Toto');
        $commentaire1->setContenu('On veut les photos !');
        
        //création d'un deuxième commentaire
        $commentaire2 = new Commentaire;
        $commentaire2->setAuteur('Choupy');
        $commentaire2->setContenu('Les photos arrivent !');
        
        // On lie les commentaires à l'article
        $commentaire1->setArticle($article);
        $commentaire2->setArticle($article);
        
        //création de l'entité Image
        $image = new Image;
        $image->setUrl('http://uploads.siteduzero.com/icones/478001_479000/478657.png');
        $image->setAlt('Logo Symfony2');

        //on lie l'image à l'article
        $article->setImage($image);

        //on récupère l'entity manager
        $em = $this->getDoctrine()->getManager();
        // 1 : on persiste l'entité
        $em->persist($article);
        // Pour cette relation pas de cascade, car elle est définie dans l'entité Commentaire et non Article
        // On doit donc tout persister à la main ici
        $em->persist($commentaire1);
        $em->persist($commentaire2);
        // 2 : on flush tout ce qui a été persisté avant
        $em->flush();
  */      
        
        // Traitement du POST et vérification (service antispam)
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $antispam = $this->container->get('greg_blog.antispam');
            //TODO : trouver comment tester $contenu qui doit être récupéré dans le POST
            //        $contenu = "blahblahhttp://www.greg.com http://www.toto.com http://www.test.com";

            if ($antispam->isSpam($contenu)) {
                throw new \Exception('Votre message a été détecté comme spam !');
            }
            // On fait le lien requ$ete <-> formulaire
            // A partir e maintenant, la variable $article contient les valeurs entrées dans le formulaire
            $form->bind($request);
            
            // On vérifie que les valeurs entrées sont correctes
            if ($form->isValid()) {
                // On enregistre notre objet $article dans la BD
                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();
            }
            $this->get('session')->getFlashBag()->add('notice', 'Article bien enregistré');
            //on redirige vers la page de visualisation de cet article
            return $this->redirect($this->generateUrl('blog_voir', array('id' => $article->getId())));
        }

        
        // À ce stade :
        // - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
        // - Soit la requête est de type POST, mais le formulaire n'est pas valide, donc on l'affiche de nouveau
 
        //on passe la méthode createView du formulaire à la vue pour qu'elle puisse afficher le formulaire
        return $this->render('GregBlogBundle:Blog:ajouter.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function modifierAction($id) 
    {
        // On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();
        
        // On récupère l'entité correspondant à l'id $id
        $article = $em->getRepository('GregBlogBundle:Article')->find($id);
        
        if ($article === null) {
            throw $this->createNotFoundException("Article[id=$id] inexistant.");
        }
        
        //on gère la création et la gestion du formulaire
        $formBuilder = $this->createFormBuilder($article);
        $formBuilder
                ->add('date',       'date')
                ->add('titre',      'text')
                ->add('contenu',    'textarea')
                ->add('auteur',     'text')
                ->add('publication','checkbox', array('required' => false));
        
        $form = $formBuilder->getForm();
        
        return $this->render('GregBlogBundle:Blog:modifier.html.twig', array(
                    'article' => $article,
                    'form' => $form->createView()
        ));
    }

    public function supprimerAction($id) {
        //on supprime l'article correspondant à id
        // On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();
        
        // On récupère l'entité correspondant à l'id $id
        $article = $em->getRepository('GregBlogBundle:Article')->find($id);
        
        if ($article === null)
        {
            throw $this->createNotFoundException("Article[id=$id] inexistant.");
        }
        
        // On récupère toutes les catégories 
        $liste_categories = $em->getRepository('GregBlogBundle:Categorie')->findAll();
        
        // On enlève toutes les catégories de l'article
        foreach ($liste_categories as $categorie)
        {
            // On appelle la méthode removeCategories de l'entité Categorie
            // Ici $categorie est une instance de Categorie, et pas seulement un id
            $article->removeCategorie($categorie);
        }
        // On a pas modifié les categories, pas besoin de les persister
        
        // On a modifié la relation Article - Categorie
        // Il faudrait persister l'entité propriétaire pour persister la relation
        // Or l'article a été récupéré depuis Doctrine, inutile de le persister
        
        // On déclenche la modification
        $em->flush();
        
//        return new Response("Suppression OK");
        
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
