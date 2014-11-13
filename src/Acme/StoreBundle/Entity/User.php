<?php
namespace Acme\StoreBundle\Entity;

use Symfony\Component\Security\Tests\Core\Encoder\PasswordEncoder;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\ Entity
 * @ORM\ Table(name="user")
 * @UniqueEntity(fields="username", message="Username already taken")
 * @UniqueEntity(fields="email", message="Email already taken")
 * @author xxx
 *
 */

class User implements UserInterface, \Serializable{
	/**
	 * @ORM\ Id
	 * @ORM\ Column(type="integer")
	 * @ORM\ GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * @ORM\ Column(type="string", length=45, unique=true)
	 */
	protected $username;
	
	/**
	 * @ORM\ Column(type="string", length=4096)
	 */
	protected $password;
	
	/**
	 * @ORM\ Column(type="string", length=128)
	 */
	protected $email;
	
	/**
	 * @ORM\ Column(type="boolean")
	 */
	protected $isActive;
	
	public function __construct(){
		$this->isActive = true;
	}

    /**
     * Get Id
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }
    
    

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }
    
    public function getSalt(){
    	return null;
    }
    
    public function getRoles(){
    	return array('ROLE_USER');
    }
    
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Security\Core\User.UserInterface::eraseCredentials()
     * @inheritDoc
     */
    public function eraseCredentials(){
    	
    }
    
    /**
     * @see \Serializable::serialize()
     */
    public function serialize(){
    	return serialize(array(
    			$this->id,
    			$this->username,
    			$this->password,
    			));
    }
    
    public function unserialize($serialized){
    	list(
    			$this->id,
    			$this->username,
    			$this->password,
    			) = unserialize($serialized);
    }
    
    
    // PLAIN PASSWORD FOR ENCODE
    private $plainPassword;
    
    public function getPlainPassword(){
    	return $this->plainPassword;
    }
    
    public function setPlainPassword($plainPassword){
    	$this->plainPassword = $plainPassword;
    }
    
    ///===///
    

    
}
