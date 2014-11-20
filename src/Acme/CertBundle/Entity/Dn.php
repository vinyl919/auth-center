<?php
namespace Acme\CertBundle\Entity;

class Dn {
	protected $countryName;
	protected $stateOrProvinceName;
	protected $localityName;
	protected $organisationName;
	protected $organisationUnitName;
	protected $commonName;
	protected $emailAddress;
	protected $caName;
	protected $caPassword;
	
	public function getCountryName(){
		return  $this->countryName;
	}
	public function getStateOrProvinceName(){
		return  $this->stateOrProvinceName;
	}
	public function getLocalityName(){
		return  $this->localityName;
	}               
	public function getOrganisationName(){
		return  $this->organisationName;
	}
	public function getOrganisationUnitName(){
		return  $this->organisationUnitName;
	}
	public function getCommonName(){
		return  $this->commonName;
	}
	public function getEmailAddress(){
		return  $this->emailAddress;
	}
	
	public function getCaName(){
		return  $this->caName;
	}
	public function getCaPassword(){
		return  $this->caPassword;
	}
	
	
	public function setCountryName($countryName){
		$this->countryName = $countryName;
	}
	public function setStateOrProvinceName($stateOrProvinceName){
		$this->stateOrProvinceName = $stateOrProvinceName;
	}
	public function setLocalityName($localityName){
		$this->localityName = $localityName;
	}
	public function setOrganisationName($organisationName){
		$this->organisationName = $organisationName;
	}
	public function setOrganisationUnitName($organisationUnitName){
		$this->organisationUnitName = $organisationUnitName;
	}
	public function setCommonName($commonName){
		$this->commonName = $commonName;
	}
	public function setEmailAddress($emailAddress){
		$this->emailAddress = $emailAddress;
	}
	
	public function setCaName($caName){
		$this->caName = $caName;
	}
	public function setCaPassword($caPassword){
		$this->caPassword = $caPassword;
	}

	
}