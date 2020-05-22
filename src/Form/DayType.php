<?php 
namespace App\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
class DayType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
		->add('name', ChoiceType::class, array(
			'expanded' => false, 
			'multiple' => false, 
			'label' => false, 
			'choices' => array('Monday' => 'lundi', 'Tuesday' => 'mardi', 'Wednesday' => 'mercredi', 'Thursday' => 'jeudi', 'Friday' => 'vendredi', 'Saturday' => 'samedi', 'Sunday' => 'dimanche', 'Everyday' => 'everyday')
		))
		->add('equivalent_name', ChoiceType::class, array(
			'expanded' => false, 
			'multiple' => false, 
			'label' => false, 
			'choices' => array('Aucun' => 'aucun', 'Monday' => 'lundi', 'Tuesday' => 'mardi', 'Wednesday' => 'mercredi', 'Thursday' => 'jeudi', 'Friday' => 'vendredi', 'Saturday' => 'samedi', 'Sunday' => 'dimanche')
		))
		->add('delete', SubmitType::class, array(
			'label' => 'Supprimer'
		))
		->add('add_meal', SubmitType::class, array(
			'label' => 'Ajouter repas'
		));
	}
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
			'data_class' => 'App\Entity\Day', 
		));
	}
}

?>
