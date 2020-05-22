<?php 
namespace App\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
class AlimentCategoryType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$allAlimentCategories = $options['allAlimentCategories']; 
		$builder
		->add('cancel', SubmitType::class, array(
			'label' => 'Retour', 
			'attr' => array(
				'formnovalidate'=>'formnovalidate'
			)
		))
		->add('description', TextType::class, array(
			'label' => false
		))
		->add('g_pro', NumberType::class, array(
			'label' => false
		))
		->add('g_hyd', NumberType::class, array(
			'label' => false
		))
		->add('g_fat', NumberType::class, array(
			'label' => false
		))
		->add('ene', NumberType::class, array(
			'label' => false
		))
		->add('add', SubmitType::class, array(
			'label' => 'Add category'
		))
		->add('selectedAlimentCategories', ChoiceType::class, array(
			'expanded' => true, 
			'multiple' => true, 
			'label' => false, 
			'choice_loader' => new CallbackChoiceLoader(function() use($allAlimentCategories) {
				$output = []; 
				foreach($allAlimentCategories as $alimentCategory) { 
					array_push($output, $alimentCategory->getId()); 
				}
				return $output; 
			}), 
			'attr' => array(
				'formnovalidate'=>'formnovalidate'
			), 
			'mapped' => false
		))
		->add('delete', SubmitType::class, array(
			'label' => 'Delete', 
			'attr' => array(
				'formnovalidate'=>'formnovalidate'
			)
		)); 
	}
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
			'data_class' => 'App\Entity\AlimentCategory', 
			'allAlimentCategories' => NULL
		));
	}
}
?>
