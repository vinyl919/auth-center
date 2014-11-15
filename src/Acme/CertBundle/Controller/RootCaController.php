<?php 

namespace Acme\CertBundle\Controller;

use Symfony\Component\BrowserKit\Response;

use Acme\StoreBundle\Entity\CA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Acme\FormBundle\Form\Type\DnType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Acme\CertBundle\Entity\Dn;

class RootCaController extends Controller{
	
	public function newAction(Request $request){
		if ($this->getRootCaFromDb() == false){
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
		} else {
			return $this->redirect($this->generateUrl('acme_user_panel'));
		}
	}
	
	public function getRootCaFromDb(){
		$rootCaInfo = $this->getDoctrine()
		->getRepository('AcmeStoreBundle:CA')
		->findOneByUserId(1);
		return $rootCaInfo;
	}
	
	public function getCaInfoAction(){
		$rootCaInfo = $this->getRootCaFromDb();
		$ca = false;
		if($rootCaInfo){
			$ca = true;
		}
		return $this->render('AcmeSiteBundle:Cert:ca-check.html.twig', array('caInfo'=>$ca));
	}
}



?>