<?php
namespace Acme\CertBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="clientCertificates")
 * @UniqueEntity(fields="certName", message="Email already taken")
 */
class ClientCertificate {
	
	
}