<?php
namespace Acme\FormBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;

class DnType extends AbstractType{
	
	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder
		->add('countryName','choice',  array(
				'label'=>'Country Name',
				'choice_list'=> new ChoiceList(array(
								'PL','EN'
						),
						array(
								'PL','EN'
						)
						)
				))
		->add('stateOrProvinceName','text',  array('label'=>'State or province name'))
		->add('localityName','text',  array('label'=>'Locality Name'))
		->add('organisationName','text',  array('label'=>'Organisation name'))
		->add('organisationUnitName','text',  array('label'=>'Organisation unit name'))
		->add('commonName', 'text', array('label'=>'Common name'))
		->add('emailAddress', 'email', array('label'=>'Email address'))
		->add('send', 'submit', array('attr'=>array('class'=>'btn btn-default')));
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