<?php 
namespace Acme\FormBundle\Entity;

class Password{
	protected $password;
	
	public function setPassword($password){
		$this->password = $password;
	}
	
	public function getPassword(){
		return $this->password;
	}
}


?>