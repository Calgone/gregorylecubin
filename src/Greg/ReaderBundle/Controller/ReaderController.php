<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ReaderController
 *
 * @author greg
 */

namespace Greg\ReaderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ReaderController extends Controller {
public function indexAction()
{
    return new Response("Hello vous êtes sur le reader.");
}

}

?>
