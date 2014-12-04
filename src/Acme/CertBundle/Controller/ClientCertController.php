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
	public function newSignedCertAction(Request $request){
		
		$dn = new Dn;
		$form = $this->createForm(new DnType(), $dn);
		$form->handleRequest($request);
		
		$certData = parent::getRootCaFromDb();
		if( $certData == false){
			return $this->redirect($this->generateUrl('user_area_root_ca_site'));
		}
		$rootCa = new CertificateManage($certData->getCaPrivKey(), $certData->getCaCert(), $password = 'xxx');
		$caCert = $rootCa->getCert();
		$caPrivKey = $rootCa->getPrivKey();
		///$error = var_dump($caPrivKey);
		//die($error);
		
		$dn = new Dn();
		$form = $this->createForm(new DnType(), $dn);
		$form->handleRequest($request);
		
		if($form->isValid()){
			$userId = $this->getUser()->getId();
			$clientCert = new Certificate($dn);
			$clientCert->getNewPrivKey();
			$clientCert->newSignedCert($caCert, $caPrivKey, 'xxx', 1);
			die('DZIALA!!!!');
			

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
}