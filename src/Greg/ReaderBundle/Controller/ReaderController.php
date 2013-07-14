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
use Greg\ReaderBundle\Entity\Channel;

class ReaderController extends Controller {

    public function indexAction($page) {
        // On affiche touts les abonnements (subs) de chaque catégorie
        $items = $this->getDoctrine()
                ->getManager()
                ->getRepository('GregReaderBundle:Item')
                ->getAllItems(20, $page);

        return $this->render('GregReaderBundle:Reader:index.html.twig', array(
                    'items' => $items,
                    'page' => $page,
                    'nbParPage' => ceil(count($items) / 20),
        ));
    }

    public function voirChannelAction(Channel $channel, $page) {
        //on récupère la liste des items
        $items = $this->getDoctrine()
                ->getManager()
                ->getRepository('GregReaderBundle:Item')
                ->getItems($channel, 10, $page);

        return $this->render('GregReaderBundle:Reader:channel.html.twig', array(
                    'channel' => $channel,
                    'items' => $items,
                    'page' => $page,
                    'nbParPage' => ceil(count($items) / 10),
        ));
    }

    public function menuAction($nombre) {
        $categories = $this->getDoctrine()
                ->getManager()
                ->getRepository('GregReaderBundle:Category')
                ->getCategories();
        return $this->render('GregReaderBundle:Reader:menu.html.twig', array(
                    'categories' => $categories,
        ));
    }

    public function channelAddAction() {
        $channel = new Channel();

        $formBuilder = $this->createFormBuilder($channel);
        $formBuilder->add('title', 'text')
                ->add('type', 'text')
                ->add('xmlUrl', 'text')
                ->add('htmlUrl', 'text')
                ->add('description', 'textarea')
                ->add('category', 'entity', array(
                    'class' => 'GregReaderBundle:Category',
                    'property' => 'name'
        ));
        $form = $formBuilder->getForm();

        //traitement du POST
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            //TODO: check pour spam

            $form->bind($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($channel);
                $em->flush();
            }
            $this->get('session')->getFlashBag()->add('notice', 'Abonnement enregistré');
            return $this->redirect($this->generateUrl('reader_index'));
        }
        return $this->render('GregReaderBundle:Reader:channel_add.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    public function channelsManageAction() {
        $categories = $this->getDoctrine()
                ->getManager()
                ->getRepository('GregReaderBundle:Category')
                ->getCategories();

        return $this->render('GregReaderBundle:Reader:channels_manage.html.twig', array(
                    'categories' => $categories,
        ));
    }

    public function channelEditAction($id) {
 
        $em = $this->getDoctrine()->getManager();
        $channel = $em->getRepository('GregReaderBundle:Channel')->find($id);
        if ($channel === null) {
            throw $this->createNotFoundException("Channel[id=" . $id . "] inexistant");
        }

        $formBuilder = $this->createFormBuilder($channel);
        $formBuilder
                ->add('title', 'text')
                ->add('type', 'text')
                ->add('xmlUrl', 'text')
                ->add('htmlUrl', 'text')
                ->add('description', 'textarea')
                ->add('category', 'entity', array(
                            'class' => 'GregReaderBundle:Category',
                            'property' => 'name'
                        ));
        $form = $formBuilder->getForm();
        
        return $this->render('GregReaderBundle:Reader:channel_edit.html.twig', array(
                    'channel' => $channel,
                    'form' => $form->createView(),
        ));
    }

}

?>
