<?php

namespace Acme\UserBundle\Controller;

use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Acme\UserBundle\Entity\User;
use Acme\FormBundle\Form\Type\RegisterType;
use Doctrine\ORM\EntityManager;


class RegisterController extends Controller {
	public function registerAction(Request $request){
		
		$user = new User();
		$form = $this->createForm(new RegisterType(), $user);
		$form->handleRequest($request);
		
		if ($form->isValid()){
			
			$factory = $this->get('security.encoder_factory');
			$encoder = $factory->getEncoder($user);
			$password = $encoder->encodePassword($user->getPlainPassword(), $user->getSalt());
			$user->setPassword($password);
			$em = $this->getDoctrine()->getManager();
			$em->persist($user);
			$em->flush();
			return $this->render('AcmeSiteBundle:Default:success-message.html.twig', array(
					'title' => 'Witaj w Auth-center.pl',
					'message' => 'Rejestracja przebiegła pomyślnie. Gratulacje!'
					));
		}
		
		//return new Response('Dodano usera: '.$user->getUsername());
		return $this->render('AcmeSiteBundle:Panel:login_register.html.twig', array('form'=>$form->createView()));
	}
}