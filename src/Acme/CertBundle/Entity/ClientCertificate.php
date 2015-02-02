<?php
namespace Acme\CertBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity
 * @ORM\Table(name="clientCertificates")
 * @UniqueEntity(fields="certName", message="Nazwa jest już zajęta")
 */
class ClientCertificate {
	/**
	 * @ORM\ Id
	 * @ORM\ Column(type="integer")
	 * @ORM\ GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	/**
	 * @ORM\ Column(type="integer")
	 *
	 */
	protected $userId;
	
	/**
	 * @ORM\ Column(type="integer")
	 */
	protected $active;
	/**
	 * @ORM\ Column(type="integer")
	 */
	protected $caId;
	
	/**
	 * @ORM\ Column(type="string")
	 */
	protected $clientPrivKey;
	
	/**
	 * @ORM\ Column(type="string")
	 */
	protected $clientCert;
	
	/**
	 * @ORM\ Column(type="string")
	 */
	protected $clientCertName;
	
	/**
	 * @ORM\ Column(type="date")
	 */
	protected $date;
	
	
	

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
     * @return ClientCertificate
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
     * Set clientPrivKey
     *
     * @param string $clientPrivKey
     * @return ClientCertificate
     */
    public function setClientPrivKey($clientPrivKey)
    {
        $this->clientPrivKey = $clientPrivKey;

        return $this;
    }

    /**
     * Get clientPrivKey
     *
     * @return string 
     */
    public function getClientPrivKey()
    {
        return $this->clientPrivKey;
    }

    /**
     * Set clientCert
     *
     * @param string $clientCert
     * @return ClientCertificate
     */
    public function setClientCert($clientCert)
    {
        $this->clientCert = $clientCert;

        return $this;
    }

    /**
     * Get clientCert
     *
     * @return string 
     */
    public function getClientCert()
    {
        return $this->clientCert;
    }

    /**
     * Set clientCertName
     *
     * @param string $clientCertName
     * @return ClientCertificate
     */
    public function setClientCertName($clientCertName)
    {
        $this->clientCertName = $clientCertName;

        return $this;
    }

    /**
     * Get clientCertName
     *
     * @return string 
     */
    public function getClientCertName()
    {
        return $this->clientCertName;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return ClientCertificate
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set caId
     *
     * @return ClientCertificate
     */
    public function setCaId($caId)
    {
        $this->caId = $caId;

        return $this;
    }

    /**
     * Get caId
     *
     * @return \int 
     */
    public function getCaId()
    {
        return $this->caId;
    }
    
    public function setActive($active){
    	$this->active = $active;
    }
    
    public function getActive(){
    	return $this->active;
    }
}
