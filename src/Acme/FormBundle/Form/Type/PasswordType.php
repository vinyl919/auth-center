<?php
namespace Acme\FormBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PasswordType extends AbstractType{
	
	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder
				->add('password', 'password', array('label'=>'Hasło zabezpieczające'))
				->add('send', 'submit', array('attr'=>array('class'=>'btn btn-default submit'), 'label'=>'Pobierz'));
	}
	
	public function getName(){
		return 'Password';
	}
	
	
	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
				'data_class'=>'Acme\FormBundle\Entity\Password',
		));
	}
}