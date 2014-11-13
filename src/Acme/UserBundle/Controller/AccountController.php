<?php

namespace Acme\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AccountController extends Controller{
	
	public function indexAction(){
		return $this->render('AcmeSiteBundle:Account:userMainPage.html.twig');
	}
}