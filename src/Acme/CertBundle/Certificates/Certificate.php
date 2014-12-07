<?php 
namespace Acme\CertBundle\Certificates;

use Doctrine\ORM\EntityNotFoundException;
	use Acme\CertBundle\Entity\Dn;
use Acme\FormBundle\Form\Type\DnType;


class Certificate {
	
	protected $iv = '78495147';
	protected $privateKey;
	protected $cert;
	protected $signedCert;
	protected $method = 'DES3';
	
	public $caName;
	public $caPassword;
	
	public $defaultConfig = array(
			'digest_alg' => 'sha512',
			'config' => '/etc/ssl/openssl.cnf',
			'encrypt_key_cipher' => OPENSSL_CIPHER_3DES,
			'private_key_bits' => 4096
	);
	
	//public $defaultConfig = null;
	
	public $dn = array(
			'countryName'=>'',
			'stateOrProvinceName'=>'',
			'localityName'=>'',
			'organizationName'=>'',
			'organizationalUnitName'=>'',
			'commonName'=>'',
			'emailAddress'=>'',
	);
	
	
	public function __construct(Dn $form){
		$this->dn = array(
				'countryName'=>$form->getCountryName(),
				'stateOrProvinceName'=>$form->getStateOrProvinceName(),
				'localityName'=>$form->getLocalityName(),
				'organizationName'=>$form->getOrganisationName(),
				'organizationalUnitName'=>$form->getOrganisationUnitName(),
				'commonName'=>$form->getCommonName(),
				'emailAddress'=>$form->getEmailAddress(),
		);
		
		$this->caName = $form->getCaName();
		$this->caPassword = $form->getCaName();
		
		$this->getNewPrivKey();
	}
	
	public function getNewPrivKey(){
		$newPrivKey = openssl_pkey_new($this->defaultConfig);
		//return 
		$this->privateKey = array($this->exportNewPrivKey($newPrivKey), $this->caPassword);
	}
	
	public function exportNewPrivKey($key){
		openssl_pkey_export($key, $out, $this->caPassword);
		return $out;
	}
	
	public function getPrivKey(){

		return $this->exportNewPrivKey($this->privateKey[0]);
	}
	
	public function encrypt($type){
		//die($this->caPassword);
		$password = sha1($this->caPassword);
		if($type == 'key'){
			/* $error = var_dump($this->exportNewPrivKey($this->newPrivKey));
			die($error); */
			return openssl_encrypt($this->privateKey[0], $this->method, $password, null, $this->iv);
		} else if($type == 'cert'){
			return openssl_encrypt($this->signedCert, $this->method, $password, null, $this->iv);
		} else {
			die ('Bledny wybor typu danych');
			
		}
	}
	
	
	public function decrypt($data, $password){
		$password = sha1($password);
		$decrypted = openssl_decrypt($data, $this->method, $password, null, $this->iv);
		//die($data.'<br />'.$password.'<br />'.$this->iv);
		return $decrypted;
		//die($data.' ==================== '.openssl_error_string());
	}
	
	public function getNewCsr(){
		$newCsr = openssl_csr_new($this->dn, $this->privateKey);
		//die($newCsr);
		return $newCsr;
	}
	
	public function singCert($csr, $caCert, $caKey, $days, $config, $id){
		$signedCert = openssl_csr_sign($csr, $caCert, $caKey, $days, $config, $id);
		openssl_x509_export($signedCert, $certificate);
		$this->signedCert = $certificate;
	}
	
	public function selfSignedCert($days){
		//die($this->newPrivKey);
		$privKey = $this->privateKey;
		$signedCert = $this->singCert($this->getNewCsr(), null, $privKey, $days, $this->defaultConfig, 1);
		//die ($signedCert);
		//return $this->signedCert;
	}
	
	
	public function newSignedCert($caCert, $caPrivKey, $password, $serial, $days = 365){
		$caPrivKeyArray = array($caPrivKey, $password);
		//$error = var_dump($password);
		//die($error);
		$this->singCert($this->getNewCsr(), $caCert, $caPrivKeyArray, $days, $this->defaultConfig, $serial);
	}
	public function getSignedCert(){
		return $this->signedCert;
	}
	
	/*
	 public function getNewPkey($password){
	$this->newPrivKey = openssl_pkey_new($this->defaultConfig);
	return $this->privKeyExport($password);
	}
	public function getFilePkey($config){
	$filePrivKey = openssl_pkey_get_private(file_get_contents('ADD PATH'), 'ADD PASSWWORD' );
	return $filePrivKey;
	//$this->displayErrors();
	}
	public function privKeyExport ($password){
	if(openssl_pkey_export($this->newPrivKey, $privKey, $password)){
	return $privKey;
	} else {
	return false;
	}
	}
	public function pkeyExportToFile ($name, $password){
	$fileName = $name.'pem';
	if (openssl_pkey_export_to_file($this->privKey, $fileName, $password)){
	return true;
	} else {
	return false;
	}
	}
	
	*/
	
}



?>