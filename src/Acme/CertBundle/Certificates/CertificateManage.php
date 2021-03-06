<?php
namespace Acme\CertBundle\Certificates;

use Acme\CertBundle\Certificates\Certificate;

class CertificateManage extends Certificate{
	
	protected $privKey;
	protected $cert;
	
	public function __construct($key, $cert, $password){
		
		$this->privKey = parent::decrypt($key, $password);
		//die($cert);
		$this->cert = parent::decrypt($cert, $password);
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
	
	public function exportToFile($type, $password, $uid, $name){
		$basepath = WEB_DIRECTORY.'/tmp/cert/'.$uid;
		if(!file_exists($basepath)){
			mkdir($basepath, 0777, true);
		}
		if($type == 'key'){
			$outfilename = $basepath.'/'.$name;
			openssl_pkey_export_to_file($this->privKey, $outfilename, $password);
			return $outfilename;
		} else if($type == 'cert'){
			$outfilename = $basepath.'/'.$name;
			openssl_x509_export_to_file($this->cert, $outfilename);
			return $outfilename;
		} else {
			return 'Błędny wybór typu';
		}
	}
}