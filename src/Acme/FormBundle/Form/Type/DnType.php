<?php
namespace Acme\FormBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;

class DnType extends AbstractType{
	
	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder
		->add('rootCaPassword', 'password', array('label'=>'Hasło głównego certyfikatu'))
		->add('caName', 'text', array('label'=>'Nazwa Certyfikatu'))
		->add('caPassword', 'repeated',  array(
				'type'=>'password',
				'invalid_message'=>'Podane hasła różnią się',
				'first_options'=>array('label'=>'Hasło zabezpieczające'),
				'second_options'=>array('label'=>'Powtórz hasło')
		))
		->add('countryName','choice',  array(
				'label'=>'Kraj',
				'choice_list'=> new ChoiceList(array(
						'PL','EN'),
						array(
								'PL','EN'))
		))
		->add('stateOrProvinceName','text',  array('label'=>'Województwo lub stan'))
		->add('localityName','text',  array('label'=>'Miasto'))
		->add('organisationName','text',  array('label'=>'Nazwa organizacji'))
		->add('organisationUnitName','text',  array('label'=>'Nazwa jednostki'))
		->add('commonName', 'text', array('label'=>'Domena'))
		->add('emailAddress', 'email', array('label'=>'Adres e-mail'))			
		->add('send', 'submit', array('attr'=>array('class'=>'btn btn-default submit'), 'label'=>'Utwórz Certyfikat'));
	}
	
	public function getName(){
		return 'Dn';
	}
	
	
	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
				'data_class'=>'Acme\CertBundle\Entity\Dn',
				));
	}
	

}