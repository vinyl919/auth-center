<?php
namespace Acme\SiteBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;	
use Symfony\Component\Security\Core\SecurityContext;


class IsloggedController extends Controller {
	
	public function login_checkAction(){
		
		$token = $this->get('security.context')->getToken()->getUser();
		
		if ($token == 'anon.'){
			return $this->render('AcmeSiteBundle:Default:is-logged.html.twig', array(
					'path'=>$this->generateUrl('acme_user_login'), 
					'action'=>'Zaloguj się lub zarejestruj'
					));
		} else {
			return $this->render('AcmeSiteBundle:Default:is-logged.html.twig', array(
					'path'=>$this->generateUrl('acme_user_panel'), 
					'token'=>$token, 'action'=>'Panel administracyjny'
					));
		}
	}
}