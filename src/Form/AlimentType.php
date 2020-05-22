<?php 
namespace App\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
class AlimentType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		// var_dump($options['allAlimentCategories']); echo("<br />"); 
		$allAlimentCategories = $options['allAlimentCategories']; 
		$builder
		->add('aliment_category_id', ChoiceType::class, array(
			'expanded' => false, 
			'multiple' => false, 
			'label' => false, 
			'choice_loader' => new CallbackChoiceLoader(function() use($allAlimentCategories) {
				foreach($allAlimentCategories as $alimentCategory) { 
					$output[$alimentCategory->getDescription()]=$alimentCategory->getId(); 
				}
				return $output; 
			})
		))
		->add('quantity', NumberType::class, array(
			'label' => false
		))
		->add('delete', SubmitType::class, array(
			'label' => 'Supprimer'
		))
		; 
	}
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
			'data_class' => 'App\Entity\Aliment', 
			'allAlimentCategories' => NULL
		));
	}
}
?>
