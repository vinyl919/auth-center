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
		if ($this->getCertFromDb('AcmeCertBundle:CA') == false){
			$dn = new Dn;
			$form = $this->createForm(new DnType(), $dn);
			$form->remove('rootCaPassword')
				->remove('days')
				->remove('keyLength');
			$form->handleRequest($request);
		
			if($form->isValid()){
				$userId = $this->getUser()->getId();
				//return $this->render('AcmeSiteBundle:Default:dump.html.twig', array('data'=>$userId));
				$storeCertData = new Certificate($dn);
				$key1 = $storeCertData->getNewPrivKey();
				$storeCertData->selfSignedCert('365');
				
				//$key = $storeCertData->getPrivKey();
				//die($key1.'<br />'.$key);
				//$encryptedPkey = openssl_encrypt( $pkey, 'DES3', $dn->getCaPassword(), null, $this->iv );
				//$decrypted = openssl_decrypt($pkey, 'DES3', $dn->getCaPassword(), null, $this->iv);
				$storeDn = new CA();
				$storeDn->setCAName($dn->getCaName())
						->setUSERId($userId)
						->setCAPrivKey($storeCertData->encrypt('key'));
				$storeDn ->setCACert($storeCertData->encrypt('cert'))
						 ->setDate(new \DateTime(date('Y-m-d')));
				$em = $this->getDoctrine()->getManager();
				$em->persist($storeDn);
				try {
				$em->flush();
				} catch (PDOException $e){
				return new Response($e);
				}
				return $this->render('AcmeSiteBundle:Default:success-message.html.twig', array('message'=>'Certyfikat ROOT CA został utworzony poprawnie.'));
				}
		
		return $this->render('AcmeSiteBundle:Form:dn.html.twig', array('dnForm'=>$form->createView(), 'title'=>'Tworzenie lokalnego Centrum Autoryzacji'));
		} else {
			return $this->redirect($this->generateUrl('acme_user_panel'));
		}
	}
	
	public function getCertFromDb($repository, $id = 1){

		if($repository === 'AcmeCertBundle:CA'){
			$rootCaInfo = $this->getDoctrine()
			->getRepository($repository)
			->findOneBy(array('userId' => $this->getUser()->getId()));
		} else {
			$rootCaInfo = $this->getDoctrine()
			->getRepository($repository)
			->findOneBy(array('userId' => $this->getUser()->getId(), 'id' => $id));
		}
		return $rootCaInfo;
	}
	
	 public function getCaInfoAction(){
	 	$rootCaInfo = $this->getCertFromDb('AcmeCertBundle:CA');
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
	
	public function getCertListAction(Request $request, $repository, $id){
		//die($repository);
		if($repository == 'CA'){
			$mode = 'root';
			$repository = 'AcmeCertBundle:CA';
		} elseif ($repository == 'Client') {
			$mode = 'client';
			$repository = 'AcmeCertBundle:ClientCertificate';
		}
		//die($repository.'<br />'.$mode);
		$error = null;
		$type = 'cert';
		$test = $this->getCertFromDb($repository, $id);
		if($test == null){
			return $this->render('AcmeSiteBundle:Default:error.html.twig', array('error'=>'Brak certyfikatu ROOT.'));
		}
		
		$password = new Password();
		$form = $this->createForm(new PasswordType(), $password);
		//return $this->render('AcmeSiteBundle:Default:dump.html.twig', array('data'=>$form->createView()));
		$form->handleRequest($request);	
		if($form->isValid()){
			if ($form->get('downloadKey')->isClicked()){
				$type = 'key';
			}
			$passwordCheck = $this->getCaFromDbAction(true, $test, $password->getPassword(), $type, $mode);
			//die($password->getPassword());
			if($passwordCheck == false){
				return $this->render('AcmeSiteBundle:Panel:cert-download.html.twig', array('type'=>$type, 'passwdForm'=>$form->createView(), 'error'=>'Błędne hasło'));
			}
			return $passwordCheck;
		}
		return $this->render('AcmeSiteBundle:Panel:cert-download.html.twig', array('type'=>$type, 'passwdForm'=>$form->createView(), 'error'=>$error));
	}
	
	public function passwordCheck($password, $repository, $id = 1){
		if($repository == 'CA'){
			$repository = 'AcmeCertBundle:CA';
			$data = $this->getCertFromDb('AcmeCertBundle:CA');
			$caCert = new CertificateManage($data->getCaPrivKey(), $data->getCaCert(), $password);
		} elseif ($repository == 'client'){
			$repository = 'AcmeCertBundle:ClientCertificate';
			$data = $this->getCertFromDb($repository, $id);
			$caCert = new CertificateManage($data->getClientPrivKey(), $data->getClientCert(), $password);
		} else {
			return $this->createNotFoundException('Błędne repozytorium.');
		}
		//$data = $this->getCertFromDb($repository, $id);
		//$caCert = new CertificateManage($data->getCaPrivKey(), $data->getCaCert(), $password);
		//return $this->render('AcmeSiteBundle:Default:dump.html.twig', array('data'=>$caCert));
		$decoded = $this->isDecoded($caCert);
		if($decoded == false){
			return false;
		} else {
			return true;
		}
	}
	
	public function getCaFromDbAction($download, $data, $password, $type, $mode = 'root'){
		//$e = var_dump($data);
		//die($data);
		if($mode == 'root'){
			$caCert = new CertificateManage($data->getCaPrivKey(), $data->getCaCert(), $password);	
			$certName = 'ca.cer';
			$certKeyName = 'ca.pem';
		} else {
			$caCert = new CertificateManage($data->getClientPrivKey(), $data->getClientCert(), $password);
			$certName = $data->getClientCertName().'.cer';
			$certKeyName = $data->getClientCertName().'.pem';
		}

		//$caCert = new CertificateManage($data->getCaPrivKey(), $data->getCaCert(), $password);
		$decoded = $this->isDecoded($caCert);
		if($decoded == false){
			return false;
		}
		if($download == false){
			return false;
		}
		
		if($type == 'cert'){
			$path = $caCert->exportToFile('cert', $password, $this->getUser()->getId(), $certName);
			$filename = WEB_DIRECTORY.'/tmp/cert/'.$this->getUser()->getId().'/'.$certName;
		} else if($type == 'key'){
			$path = $caCert->exportToFile('key', $password, $this->getUser()->getId(), $certKeyName);
			$filename = WEB_DIRECTORY.'/tmp/cert/'.$this->getUser()->getId().'/'.$certKeyName;
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
	
	public function authentication($password, $repository){
		$authenticate = $this->passwordCheck($password, $repository);
			//die($password->getPassword());
		if($authenticate == false){
			return false;
		} else {
			return true;
		}
	}
	
}



?>