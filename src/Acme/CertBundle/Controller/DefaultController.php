<?php

namespace Acme\CertBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AcmeCertBundle:Default:index.html.twig', array('name' => $name));
    }
}
