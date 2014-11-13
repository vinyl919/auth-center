<?php 

namespace Acme\CertBundle\Controller;

use Symfony\Component\BrowserKit\Response;

use Acme\StoreBundle\Entity\CA;
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
		
		if($form->isValid()){
			$userId = $this->getUser()->getId();
			//return $this->render('AcmeSiteBundle:Default:dump.html.twig', array('data'=>$userId));
			$storeDn = new CA();
			$storeDn->setCAName('Pierwszy ca')
					->setUSERId($userId)
					->setCAPrivKey('priv key')
					->setCACert('CA CERT');
			$em = $this->getDoctrine()->getManager();
			$em->persist($storeDn);
			try {
			$em->flush();
			} catch (PDOException $e){
				return new Response($e);
			}
			return $this->render('AcmeSiteBundle:Form:dn.html.twig', array('dnForm'=>$form->createView(), 'title'=>'Dn form'));
		}
		
		return $this->render('AcmeSiteBundle:Form:dn.html.twig', array('dnForm'=>$form->createView(), 'title'=>'Dn form'));
	}
	
	public function getAction(){
		
		$ca = $this->getDoctrine()
					->getRepository('AcmeStoreBundle:CA')
					->findOneByUserId(1);
		
		if(!$ca){
			return $this->render('AcmeSiteBundle:Account:userMainPage.html.twig');
		}
		return $this->render('AcmeSiteBundle:Default:dump.html.twig', array('data'=>$ca->getCaPrivKey()));
	}
}



?>