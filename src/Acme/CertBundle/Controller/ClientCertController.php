<?php
namespace Acme\CertBundle\Controller;

use Acme\SiteBundle\AcmeSiteBundle;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;


use Symfony\Component\Form\FormError;

use Acme\CertBundle\Controller\RootCaController;
use Acme\FormBundle\Form\Type\PasswordType;
use Symfony\Component\HttpFoundation\Response;
use Acme\FormBundle\Entity\Password;
use Acme\CertBundle\Certificates\CertificateManage;
use Acme\CertBundle\Certificates\Certificate;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Acme\FormBundle\Form\Type\DnType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Acme\CertBundle\Entity\Dn;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Acme\CertBundle\Entity\ClientCertificate;
use Acme\FormBundle\Form\Type\AuthenticateType;

class ClientCertController extends RootCaController{
	
	public function certsListAction(){		
		$data = $this->getDataFromDb('AcmeCertBundle:ClientCertificate');
		if(!$data){
			return $this->render('AcmeSiteBundle:Cert:cert-list.html.twig');
		};
		$list = array();
		
		foreach($data as $key){
			if ($key->getActive() > 0 ){
			$list[]=array('id'=>$key->getId(), 'name'=>$key->getClientCertName(), 'date'=>$key->getDate());
			}
		}
		//$e = var_dump($list);
		
		return $this->render('AcmeSiteBundle:Cert:cert-list.html.twig', array('data'=>$list));
	} 
	
	
	public function getCaForNewCertAction(Request $request){
		
			$password = new Password();
			$form = $this->createForm(new PasswordType(), $password);
			$form->handleRequest($request);
			$rootCaPassword  = $password->getPassword();
			if($form->isValid()){
				$passwordCheck = parent::passwordCheck($rootCaPassword);
				//die($password->getPassword());
				if($passwordCheck == false){
					return $this->render('AcmeSiteBundle:Form:password.html.twig', array('passwdForm'=>$form->createView(), 'error'=>'Błędne hasło'));
				} 
			//$passwordCheck = xxxxx;
			//return $this->render('AcmeSiteBundle:Panel:cert-download.html.twig', array('type'=>$type, 'passwdForm'=>$form->createView(), 'error'=>$error));
			//return $this->redirect($this->generateUrl('user_area_new_client_cert',array('password: '=>$rootCaPassword)));
			return $this->forward('AcmeCertBundle:ClientCert:newSignedCert', array('password'=>$rootCaPassword));
			}
		return $this->render('AcmeSiteBundle:Form:password.html.twig', array('passwdForm'=>$form->createView()));
	}
	
	public function newSignedCertAction(Request $request){
		// sprawdzenie istnienia root ca
		//die(parent::getCertFromDb('AcmeCertBundle:CA'));
		if (parent::getCertFromDb('AcmeCertBundle:CA') === null){
			return $this->render('AcmeSiteBundle:Cert:root-ca-site.html.twig', array('info'=>'Nie posiadasz jeszcze własnego centrum autoryzacji'));
		}
		
		
		$dn = new Dn;
		$form = $this->createForm(new DnType(), $dn);
		//$form->remove('rootCaPassword');
		$form->handleRequest($request);
		if($form->isValid()){
			if($passwordCheck = parent::passwordCheck($dn->getRootCaPassword(), 'CA') == false){
				$form->addError(new FormError('Błędne hasło certyfikatu ROOT'));
				return $this->render('AcmeSiteBundle:Form:dn.html.twig', array('dnForm'=>$form->createView(), 'title'=>'Wystawianie nowego certyfikatu'));
				
			}
		$certData = parent::getCertFromDb('AcmeCertBundle:CA');
		
		if( $certData == false){
			return $this->redirect($this->generateUrl('user_area_root_ca_site'));
		}
		$rootCa = new CertificateManage($certData->getCaPrivKey(), $certData->getCaCert(), $dn->getRootCaPassword());
		$caCert = $rootCa->getCert();
		$caPrivKey = $rootCa->getPrivKey();
		$caId = $certData->getId();
		///$error = var_dump($caPrivKey);
		//die('tu'.$caId);
		
		
			$storeClientCert = new ClientCertificate();
			
			$userId = $this->getUser()->getId();
			$dn->setBasicConstraints('basicConstraints = CA:false');
			$clientCert = new Certificate($dn);

			
			$clientCert->getNewPrivKey();
			$clientCert->newSignedCert($caCert, $caPrivKey, $dn->getRootCaPassword(), $this->getSerial());

			$storeClientCert->setDate(new \DateTime(date('Y-m-d')))
							->setClientCertName($dn->getCaName())
							->setCaId((int)$caId)
							->setUserId($userId)
							->setClientPrivKey($clientCert->encrypt('key'))
							->setClientCert($clientCert->encrypt('cert'))
							->setActive(1);
			
			$em = $this->getDoctrine()->getManager();
			$em->persist($storeClientCert);
			try {
				$em->flush();
			} catch (PDOException $e){
				var_dump($e);
				return new Response($e);
			}
			
			return $this->render('AcmeSiteBundle:Default:success-message.html.twig', array('message'=>'Nowy certyfikat został utworzony poprawnie'));
			
		}
		
		return $this->render('AcmeSiteBundle:Form:dn.html.twig', array('dnForm'=>$form->createView(), 'title'=>'Wystawianie nowego certyfikatu'));
	}
	
	public function getSerial(){
		$serialPrepare = $this->getDataFromDb('AcmeCertBundle:ClientCertificate');
		if(!$serialPrepare){
			$serial = 2;
			return $serial;
		}
		$lastId = end($serialPrepare);
		$serial = $lastId->getId();
		$serial += 1;
		return $serial;
	}
	
	public function getDataFromDb($repository){
		$userId = $this->getUser()->getId();
		$data = $this->getDoctrine()
			->getRepository($repository)
			->findByUserId($userId);		
		return $data;
				
		//$e = var_dump($data);
		//die($e);
	}
	
	public function unsetCertAction($id, Request $request){
		
		$em = $this->getDoctrine()->getManager();
		$cert = $em	->getRepository('AcmeCertBundle:ClientCertificate')
		->findOneBy(array(
				'userId' => $this->getUser()->getId(),
				'id' => $id
		));
		if(!$cert){
			throw $this->createNotFoundException('Nie znaleziono certyfikatu urzytkownika o id: '.$id);
		}
		
		
		$password = new Password();
		$form = $this->createForm(new AuthenticateType(), $password);
		$form->handleRequest($request);	
		
		if ($form->isValid()){	
		$authenticate = parent::passwordCheck($password->getPassword(), 'client', $id);
			if ($authenticate == true){
				$cert->setActive(0);
				$em->flush();
				return $this->render('AcmeSiteBundle:Default:success-message.html.twig', array('message'=>'Certyfikat został skasowany poprawnie.'));
			} else {
				return $this->render('AcmeSiteBundle:Form:password.html.twig', array('passwdForm'=>$form->createView(), 'error'=>'Błędne hasło.'));
		
			}
		}
		return $this->render('AcmeSiteBundle:Form:password.html.twig', array('passwdForm'=>$form->createView(), 'message'=>'Podaj hasło certyfikatu: '.$cert->getClientCertName()));
		
	}
}