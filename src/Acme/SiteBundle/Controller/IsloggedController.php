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
			return $this->render('AcmeSiteBundle:Default:is-logged.html.twig', array('path'=>$this->generateUrl('acme_user_login'), 'action'=>'Register or sign in'));
		} else {
			return $this->render('AcmeSiteBundle:Default:is-logged.html.twig', array('path'=>'account', 'token'=>$token, 'action'=>'My account'));
		}
	}
}