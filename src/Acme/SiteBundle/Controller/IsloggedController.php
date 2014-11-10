<?php
namespace Acme\SiteBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;	
use Symfony\Component\Security\Core\SecurityContext;


class IsloggedController extends Controller {
	
	public function login_checkAction(){
		
		$securityContext = $this->container->get('security.context');
		if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
			$user = 'ZALOGOWANO!';
		} else {
			$user = 'Nie zalogowano';
		}
		return $this->render('AcmeSiteBundle:Default:dump.html.twig', array('data'=>$user));
	}
}