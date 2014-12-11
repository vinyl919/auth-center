<?php
namespace Acme\CertBundle\Certificates;

use Acme\CertBundle\Certificates\Certificate;

class CertificateManage extends Certificate{
	
	protected $privKey;
	protected $cert;
	public $caPassword;
	
	public function __construct($key, $cert, $caPassword){
		
		$this->privKey = array($key, $caPassword);
		//die($cert);
		$this->cert = $cert;
		$this->caPassword = $caPassword;
		
		//die($this->cert);
// 		$e='';
// 		while(openssl_error_string()){
// 			$e.=openssl_error_string().'<br />';
// 		}
		
	}
	
	public function getPrivKey(){
		return $this->privKey;
	}
	
	public function getCert(){
		return $this->cert;
	}
	
	public function getCaPassword(){
		return $this->caPassword;
	}
	
	public function exportToFile($type, $password, $uid){
		$basepath = WEB_DIRECTORY.'/tmp/cert/'.$uid;
		if(!file_exists($basepath)){
			mkdir($basepath, 0777, true);
		}
		if($type == 'key'){
			$outfilename = $basepath.'/ca.pem';
			openssl_pkey_export_to_file($this->privKey[0], $outfilename, $password);
			return $outfilename;
		} else if($type == 'cert'){
			$outfilename = $basepath.'/ca.cer';
			openssl_x509_export_to_file($this->cert, $outfilename);
			return $outfilename;
		} else {
			return 'Błędny wybór typu';
		}
	}
}