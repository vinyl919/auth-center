<?php 
namespace Acme\CertBundle\Certificates;

use Doctrine\ORM\EntityNotFoundException;
	use Acme\CertBundle\Entity\Dn;
use Acme\FormBundle\Form\Type\DnType;


class Certificate {
	
	protected $iv = '78495147';
	protected $newPrivKey;
	protected $cert;
	
	public $caName;
	public $caPassword;
	
	public $defaultConfig = array(
			'digest_alg' => 'sha1',
			'config' => '/etc/ssl/openssl.cnf',
			'encrypt_key_cipher' => OPENSSL_CIPHER_3DES,
			'private_key_bits' => 4096
	);
	
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
	}
	
	public function getNewPrivKey(){
		$newPrivKey = openssl_pkey_new($this->defaultConfig);
		return $this->exportNewPrivKey($newPrivKey, $this->caPassword);
	}
	
	protected function exportNewPrivKey($key, $password){
		openssl_pkey_export($key, $privKey, $password);
		$this->newPrivKey = $privKey;
		//openssl_pkey_export_to_file($key,__DIR__."/../ca.pem", $password);
		return $this->newPrivKey;
	}
	
	public function encrypt($type){
		if($type == 'key'){
			return openssl_encrypt($this->newPrivKey, 'DES3', $this->caPassword, null, $this->iv);
		} else if($type == 'cert'){
			return openssl_encrypt($this->cert, 'DES3', $this->caPassword, null, $this->iv);
		} else {
			die ('Bledny wybor typu danych');
			
		}
		
		
	}
	
	public function decrypt($data, $password){
		return openssl_decrypt($data, 'DES3', $password, null, $this->iv);
	}
	
	public function getNewCsr(){
		$newCsr = openssl_csr_new($this->dn, $this->newPrivKey);
		return $newCsr;
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