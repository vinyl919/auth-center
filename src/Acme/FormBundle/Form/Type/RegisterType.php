<?php
namespace Acme\FormBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;

class RegisterType extends AbstractType{
	
	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder
		->add('username')
		->add('plainPassword', 'repeated', array(
				'type'=>'password',
				'invalid_message'=>'Hasla sa inne',
				'first_options'=>array('label'=>'Password'),
				'second_options'=>array('label'=>'Repeat password')
				))
		->add('email', 'email')
		->add('register', 'submit');
	}
	
	public function getName(){
		return 'Register';
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
				'data_class'=>'Acme\StoreBundle\Entity\User'
				));
	}
}