<?php 
namespace Acme\CertBundle\Certificates;
use Acme\CertBundle\Entity\Dn;
use Acme\FormBundle\Form\Type\DnType;


class Certificate {
	
	public $defaultConfig = array(
			'digest_alg' => 'sha1',
			'config' => '/etc/ssl/openssl.cnf',
			'encrypt_key_cipher' => OPENSSL_CIPHER_3DES,
			'private_key_bits' => 4096
	);
	public $newPrivKey;
	public $dn = array(
			'countryName'=>'',
			'stateOrProvinceName'=>'',
			'localityName'=>'',
			'organizationName'=>'',
			'organizationalUnitName'=>'',
			'commonName'=>'',
			'emailAddress'=>'',
	);
	
	public $caName;
	public $caPassword;

	
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
	}
	
	public function getNewPrivKey(){
		$newPrivKey = openssl_pkey_new($this->defaultConfig);
		return $this->exportNewPrivKey($newPrivKey, $this->caPassword);
	}
	
	protected function exportNewPrivKey($key, $password){
		openssl_pkey_export($key, $privKey, $password);
		//openssl_pkey_export_to_file($key,__DIR__."/../ca.pem", $password);
		return $privKey;
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
	public function getNewCsr(){
	$newCsr = openssl_csr_new($this->dn, $this->newPrivKey);
	return $newCsr;
	}
	*/
	
}




?>