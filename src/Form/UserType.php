<?php 
namespace App\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
class UserType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
		->add('login', TextType::class, array(
			'label' => false 
		))
		->add('password', PasswordType::class, array(
			'label' => false
		))
		->add('checkbox', CheckboxType::class, array(
			'label'    => false,
			'required' => false,			
			'mapped' => false
		))
		->add('validate', SubmitType::class, array(
			'label' => 'Sign in'
		));
	}
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
			'data_class' => 'App\Entity\User'
		));
	}
}

?>
