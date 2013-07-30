<?php

/**
 * Description of ReaderController
 *
 * @author greg
 */

namespace Greg\ReaderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Greg\ReaderBundle\Entity\Item;
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
        $str = "";
        $categories = $this->getDoctrine()
                ->getManager()
                ->getRepository('GregReaderBundle:Category')
                ->getCategoriesWithItemsUnreadCount();
//        foreach ($categories as $cat) {
//
//                $str .= var_dump($cat);
//
//        }
//        return new Response($str);
//        return new response(var_dump($categories));
        
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
    
    public function itemMarkReadAction() {
        $request = $this->container->get('request');
        $itemId = $request->query->get('id'); //itemID
        
        $em = $this->getDoctrine()->getManager();
       
        $item = $em->getRepository('GregReaderBundle:Item')
                    ->find($itemId);
        if ($item === null) {
            throw $this->createNotFoundException("Item [id=$itemId] non trouvé.");
        }
        $channelId = $item->getChannel()->getId();
        $item->setReadDate(new \DateTime());
       
        $em->flush();
  
        $response = new Response(
                    json_encode(
                            array(
                                "code" => 100, 
                                "success" => true,
                                "channelId" => $channelId
                                 )
                                )
                            );
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    
    public function itemsMarkReadAction() { //ALL ITEMS
        
        $em = $this->getDoctrine()-> getManager();
        
        $query = $em->createQuery(
                'update GregReaderBundle:Item i 
                    set i.readDate = :now where i.readDate is null')
                ->setParameter('now', new \DateTime('now'));
        $numUpdated = $query->execute();

        $em->flush();
  
        $response = new Response(
                    json_encode(
                            array(
                                "code" => 100, 
                                "success" => true,
                                 )
                                )
                            );
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    
    public function channelMarkReadAction(Channel $channel) {
//        $request = $this->container->get('request');
//        $channelId = $request->query->get('channelId');
        
//        $em = $this->getDoctrine()->getManager();
//        if (null !== $channelId) 
//        {
//            $query = $em->createQuery('update GregReaderBundle\Entity\Channel c set readDate = now() where isnull(c.');
//        }
        $em = $this->getDoctrine()->getManager();
        
        //on récupère la liste des items
        $items = $em
                ->getRepository('GregReaderBundle:Item')
                ->getUnreadItems($channel);
        foreach ($items as $item) {
            $item->setReadDate(new \DateTime);
        }
        $em->flush();
        $response = new Response(
                    json_encode(
                            array(
                                "code" => 100, 
                                "success" => true,
                                "channelId" => $channel->getId()
                                 )
                                )
                            );
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}

?>
