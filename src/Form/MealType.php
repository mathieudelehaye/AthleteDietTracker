<?php 
namespace App\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
class MealType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
		->add('name', ChoiceType::class, array(
			'expanded' => false, 
			'multiple' => false, 
			'label' => false, 
			'choices' => array('Breakfast' => 'dejeuner', 'Dinner' => 'diner', 'Supper' => 'souper', 'Snack 1' => 'collation1', 'Snack 2' => 'collation2', 'Pre-workout' => 'pre_workout', 'During-workout' => 'during_workout', 'Post-workout' => 'post_workout')
		))
		->add('delete', SubmitType::class, array(
			'label' => 'Supprimer'
		))
		->add('add_aliment', SubmitType::class, array('label' => 'Ajouter aliment')); 
	}
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
			'data_class' => 'App\Entity\Meal'
		));
	}
}
?>
