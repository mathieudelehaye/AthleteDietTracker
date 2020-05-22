<?php 
namespace App\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
class AthleteType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$userAthletes = $options['userAthletes']; 
		$athlete_id = $options['athlete_id']; 
		$builder
		->add('selectedAthlete', ChoiceType::class, array(
			'expanded' => false, 
			'multiple' => false, 
			'label' => false, 
			'choice_loader' => new CallbackChoiceLoader(function() use($userAthletes) {
				foreach($userAthletes as $athlete) { 
					$output[$athlete->getFirstName()." ".$athlete->getSecondName()." ".$athlete->getName()]=$athlete->getId(); 
				}
				return $output; 
			}),
			'data' => $athlete_id, 
			'mapped' => false
		))
		->add('weight', NumberType::class, array(
			'label' => false
		))
		->add('select', SubmitType::class, array(
			'label' => 'Choisir'
		))
		->add('add_day', SubmitType::class, array(
			'label' => 'Ajouter jour'
		));
	}
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
			'data_class' => 'App\Entity\Athlete', 
			'userAthletes' => NULL, 
			'athlete_id' => 0
		));
	}
}

?>
