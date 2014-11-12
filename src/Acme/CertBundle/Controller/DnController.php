<?php 

namespace Acme\CertBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Acme\FormBundle\Form\Type\DnType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Acme\CertBundle\Entity\Dn;

class DnController extends Controller{
	
	public function newAction(Request $request){
		$dn = new Dn;
		$form = $this->createForm(new DnType(), $dn);
		$form->handleRequest($request);
		
		return $this->render('AcmeSiteBundle:Form:dn.html.twig', array('dnForm'=>$form->createView(), 'title'=>'Dn form'));
	}
}



?>