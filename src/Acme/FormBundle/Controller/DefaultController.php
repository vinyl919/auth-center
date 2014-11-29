<?php

namespace Acme\FormBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AcmeFormBundle:Default:index.html.twig', array('name' => $name));
    }
}
