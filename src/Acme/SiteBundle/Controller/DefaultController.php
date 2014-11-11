<?php

namespace Acme\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return new Response("It works!");
    }
    
    public function accountAction(){
    	return new Response("Logowanie dziala!");
    }
    
    public function testAction(){
    	return $this->render('AcmeSiteBundle:Default:base.html.twig', array('title'=>'Auth-Center.pl'));
    }
    
}
