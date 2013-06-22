<?php

namespace Greg\ReaderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('GregReaderBundle:Default:index.html.twig', array('name' => $name));
    }
}
