<?php
namespace Acme\CertBundle\Certificates;

use Acme\CertBundle\Certificates\Certificate;

class CertificateManage extends Certificate{
	
	protected $privKey;
	protected $cert;
	
	public function __construct($key, $cert, $password){
		$this->privKey = parent::decrypt($key, $password);
		$this->cert = parent::decrypt($cert, $password);
	}
	
	public function getPrivKey(){
		return $this->privKey;
	}
	
	public function getCert(){
		return $this->cert;
	}
}