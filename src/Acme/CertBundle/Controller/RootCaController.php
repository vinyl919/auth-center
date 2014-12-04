<?php 

namespace Acme\CertBundle\Controller;

//use Symfony\Component\BrowserKit\Response;
use Acme\FormBundle\Form\Type\PasswordType;
use Symfony\Component\HttpFoundation\Response;
use Acme\FormBundle\Entity\Password;
use Acme\CertBundle\Certificates\CertificateManage;
use Acme\CertBundle\Certificates\Certificate;
use Acme\CertBundle\Entity\CA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Acme\FormBundle\Form\Type\DnType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Acme\CertBundle\Entity\Dn;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class RootCaController extends Controller{
	
	//private $iv = 72653415;
	
	public function newAction(Request $request){
		if ($this->getRootCaFromDb() == false){
			$dn = new Dn;
			$form = $this->createForm(new DnType(), $dn);
			$form->handleRequest($request);
		
			if($form->isValid()){
				$userId = $this->getUser()->getId();
				//return $this->render('AcmeSiteBundle:Default:dump.html.twig', array('data'=>$userId));
				$storeCertData = new Certificate($dn);
				$key1 = $storeCertData->getNewPrivKey();
				
				//$key = $storeCertData->getPrivKey();
				//die($key1.'<br />'.$key);
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
	
	public function isDecoded($caCert){
		if($caCert->getCert() == false || $caCert->getPrivKey() == false){
			return null;
		} else {
			return true;
		}
	}
	
	public function getCertListAction(Request $request, $type){
		$error = null;
		$test = $this->getRootCaFromDb();
		if($test == null){
			return $this->render('AcmeSiteBundle:Default:error.html.twig', array('error'=>'Brak certyfikatu ROOT.'));
		}
		
		$password = new Password();
		$form = $this->createForm(new PasswordType(), $password);
		//return $this->render('AcmeSiteBundle:Default:dump.html.twig', array('data'=>$form->createView()));
		$form->handleRequest($request);	
		if($form->isValid()){
			$passwordCheck = $this->getCaFromDbAction(true, $password->getPassword(), $type );
			//die($password->getPassword());
			if($passwordCheck == false){
				return $this->render('AcmeSiteBundle:Panel:cert-download.html.twig', array('type'=>$type, 'passwdForm'=>$form->createView(), 'error'=>'Błędne hasło'));
			}
			return $passwordCheck;
		}
		return $this->render('AcmeSiteBundle:Panel:cert-download.html.twig', array('type'=>$type, 'passwdForm'=>$form->createView(), 'error'=>$error));
	}
	
	public function getCaFromDbAction($download, $password, $type){
		if($download == false){
			return false;
		}
		$data = $this->getRootCaFromDb();

	//	die(dump($data)); 
		$caCert = new CertificateManage($data->getCaPrivKey(), $data->getCaCert(), $password);
		//return $this->render('AcmeSiteBundle:Default:dump.html.twig', array('data'=>$caCert));
		$decoded = $this->isDecoded($caCert);
		if($decoded == false){
			return false;
		}
		
		if($type == 'cert'){
			$path = $caCert->exportToFile('cert', $password, $this->getUser()->getId());
			//return $this->render('AcmeSiteBundle:Cert:cert-download.html.twig', array('cert'=>$path, 'decrypted'=>$caCert->getPrivKey()));
		
			//return $this->fileDownload('ca.cer');
			$filename = WEB_DIRECTORY.'/tmp/cert/'.$this->getUser()->getId().'/ca.cer';
		} else if($type == 'key'){
			$path = $caCert->exportToFile('key', $password, $this->getUser()->getId());
			//return $this->render('AcmeSiteBundle:Cert:cert-download.html.twig', array('cert'=>$path, 'decrypted'=>$caCert->getPrivKey()));
			
			//return $this->fileDownload('ca.cer');
			$filename = WEB_DIRECTORY.'/tmp/cert/'.$this->getUser()->getId().'/ca.pem';
		}
		else {
			die('Błędny typ danych');
		}
		return $this->fileDownload($filename);
	}
	
	public function fileDownload($filename){
		if(!file_exists($filename)){
			throw $this->createNotFoundException($filename);
		}
		
		$response = new Response();
		$response->headers->set('Cache-Control', 'private');
		//die($filename);
		$response->headers->set('Content-type', mime_content_type($filename));
		$response->headers->set('Content-Disposition', 'attachment; filename="' . basename($filename) . '";');
		$response->headers->set('Content-length', filesize($filename));
		$response->setContent(readfile($filename));
		return $response;
	}

}



?>