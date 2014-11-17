<?php
namespace Acme\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;



/**
 * @ORM\Entity
 * @ORM\Table(name="caCertificates")
 * @UniqueEntity(fields="caName", message="Email already taken")
 */
class CA {
	
	/**
	 * @ORM\ Id
	 * @ORM\ Column(type="integer")
	 * @ORM\ GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\ Column(type="integer", unique=true)
	 *  
	 */
	protected $userId;
	
	/**
	 * @ORM\ Column(type="string")
	 */
	protected $caPrivKey;
	
	/**
	 * @ORM\ Column(type="string")
	 */
	protected $caCert;
	
	/**
	 * @ORM\ Column(type="string")
	 */
	protected $caName;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return CA
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set caPrivKey
     *
     * @param string $caPrivKey
     * @return CA
     */
    public function setCaPrivKey($caPrivKey)
    {
        $this->caPrivKey = $caPrivKey;

        return $this;
    }

    /**
     * Get caPrivKey
     *
     * @return string 
     */
    public function getCaPrivKey()
    {
        return $this->caPrivKey;
    }

    /**
     * Set caCert
     *
     * @param string $caCert
     * @return CA
     */
    public function setCaCert($caCert)
    {
        $this->caCert = $caCert;

        return $this;
    }

    /**
     * Get caCert
     *
     * @return string 
     */
    public function getCaCert()
    {
        return $this->caCert;
    }

    /**
     * Set caName
     *
     * @param string $caName
     * @return CA
     */
    public function setCaName($caName)
    {
        $this->caName = $caName;

        return $this;
    }

    /**
     * Get caName
     *
     * @return string 
     */
    public function getCaName()
    {
        return $this->caName;
    }
}
