<?php
namespace Acme\CertBundle\Controller;

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

class ClientCertController extends RootCaController{
	
	public function certsListAction(){
		$userId = $this->getUser()->getId();
		
		$data = $this->getDataFromDb('AcmeCertBundle:ClientCertificate', $userId);
		
		$list = array();
		
		foreach($data as $key){
			$list[]=array('id'=>$key->getId(), 'name'=>$key->getClientCertName(), 'date'=>$key->getDate());
		}
		$e = var_dump($data);
		die($e);
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
	
	public function newSignedCertAction(Request $request, $password = null){
		
		$dn = new Dn;
		$form = $this->createForm(new DnType(), $dn);
		$form->handleRequest($request);
		
		$certData = parent::getRootCaFromDb();
		
		if( $certData == false){
			return $this->redirect($this->generateUrl('user_area_root_ca_site'));
		}
		$rootCa = new CertificateManage($certData->getCaPrivKey(), $certData->getCaCert(), $password);
		$caCert = $rootCa->getCert();
		$caPrivKey = $rootCa->getPrivKey();
		$caId = $certData->getId();
		///$error = var_dump($caPrivKey);
		//die('tu'.$caId);
		
		$dn = new Dn();
		$form = $this->createForm(new DnType(), $dn);
		$form->handleRequest($request);
		
		if($form->isValid()){
			$storeClientCert = new ClientCertificate();
			
			$userId = $this->getUser()->getId();
			$clientCert = new Certificate($dn);
			

			
			$clientCert->getNewPrivKey();
			$clientCert->newSignedCert($caCert, $caPrivKey, $password, 1);

			$storeClientCert->setDate(new \DateTime(date('Y-m-d')))
							->setClientCertName($dn->getCaName())
							->setCaId((int)$caId)
							->setUserId($userId)
							->setClientPrivKey($clientCert->encrypt('key'))
							->setClientCert($clientCert->encrypt('cert'));
			
			$em = $this->getDoctrine()->getManager();
			$em->persist($storeClientCert);
			try {
				$em->flush();
			} catch (PDOException $e){
				return new Response($e);
			}
			die('dodano do bazy');

		/*	$storeCertData = new Certificate($dn);
			$storeCertData->getNewPrivKey();
			
			$storeCert = new ClientCertificate();
			$storeCert->setClientCertName($dn->getCaName())
					->setUserId($userId)
					->setClientPrivKey($storeCertData->encrypt('key'));
			$storeCertData->signedCert($caCert, $caPrivKey, 365, $serial);
			*/
			
		}
		
		return $this->render('AcmeSiteBundle:Form:dn.html.twig', array('dnForm'=>$form->createView(), 'title'=>'Dane nowego certyfikatu'));
	}
	
	public function getDataFromDb($repository, $userId){
		$data = $this->getDoctrine()
			->getRepository($repository)
			->findByUserId($userId);
		
		if(!$data){
			throw $this->createNotFoundException('Brak odanych użytkownika.');
		};
		
		return $data;
		
		//$e = var_dump($data);
		//die($e);
	}
}