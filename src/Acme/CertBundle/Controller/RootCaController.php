<?php 

namespace Acme\CertBundle\Controller;

use Symfony\Component\BrowserKit\Response;
use Acme\CertBundle\Certificates\CertificateManage;
use Acme\CertBundle\Certificates\Certificate;
use Acme\CertBundle\Entity\CA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Acme\FormBundle\Form\Type\DnType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Acme\CertBundle\Entity\Dn;

class RootCaController extends Controller{
	
	private $iv = 72653415;
	
	public function newAction(Request $request){
		if ($this->getRootCaFromDb() == false){
			$dn = new Dn;
			$form = $this->createForm(new DnType(), $dn);
			$form->handleRequest($request);
		
			if($form->isValid()){
				$userId = $this->getUser()->getId();
				//return $this->render('AcmeSiteBundle:Default:dump.html.twig', array('data'=>$userId));
				$storeCertData = new Certificate($dn);
				$storeCertData->getNewPrivKey();
				//$encryptedPkey = openssl_encrypt( $pkey, 'DES3', $dn->getCaPassword(), null, $this->iv );
				//$decrypted = openssl_decrypt($pkey, 'DES3', $dn->getCaPassword(), null, $this->iv);
				$storeDn = new CA();
				$storeDn->setCAName($dn->getCaName())
						->setUSERId($userId)
						->setCAPrivKey($storeCertData->encrypt('key'));
				$storeCertData->selfSignedCert('365');
				$storeDn ->setCACert($storeCertData->encrypt('cert'))
						 ->setDate(new \DateTime(date('Y-m-d')));
				$em = $this->getDoctrine()->getManager();
				$em->persist($storeDn);
				try {
				$em->flush();
				} catch (PDOException $e){
				return new Response($e);
				}
				return $this->redirect($this->generateUrl('acme_user_panel'));
			}
		
		return $this->render('AcmeSiteBundle:Form:dn.html.twig', array('dnForm'=>$form->createView(), 'title'=>'Dn form'));
		} else {
			return $this->redirect($this->generateUrl('acme_user_panel'));
		}
	}
	
	public function getRootCaFromDb(){
		$rootCaInfo = $this->getDoctrine()
		->getRepository('AcmeCertBundle:CA')
		->findOneByUserId($this->getUser()->getId());
		return $rootCaInfo;
	}
	
	public function getCaInfoAction(){
		$rootCaInfo = $this->getRootCaFromDb();
		$ca = false;
		if($rootCaInfo){
			$ca = true;
		}
		return $this->render('AcmeSiteBundle:Cert:ca-check.html.twig', array('caInfo'=>$ca));
	}
	
	public function getCaFromDbAction(){
		$data = $this->getRootCaFromDb();
		$caCert = new CertificateManage($data->getCaPrivKey(), $data->getCaCert(), '111');
		return $this->render('AcmeSiteBundle:Cert:cert-download.html.twig', array('cert'=>$caCert->getCert(), 'decrypted'=>$caCert->getPrivKey()));
	}
}



?>