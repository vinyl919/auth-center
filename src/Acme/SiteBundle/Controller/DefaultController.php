<?php

namespace Acme\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AcmeSiteBundle:Default:base.html.twig', array('title'=>'Twoje centrum autoryzacji'));
    }
    
    public function accountAction(){
    	return new Response("Logowanie dziala!");
    }
    
    public function testAction(){
    	return $this->render('AcmeSiteBundle:Default:base.html.twig', array('title'=>'Auth-Center.pl'));
    }
    
    public function loginRegisterAction(){
    	return $this->render('AcmeSiteBundle:Panel:login_register.html.twig');
    }
    
    public function caSiteAction(){
    	return $this->render('AcmeSiteBundle:Cert:root-ca-site.html.twig', array('title'=>'Twój Root CA'));
    }
}
