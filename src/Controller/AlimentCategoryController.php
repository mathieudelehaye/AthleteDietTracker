<?php
namespace App\Controller;
use App\Entity\AlimentCategory;
use App\Form\AlimentCategoryType;
use ArrayObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AlimentCategoryController extends AbstractController {
    /**
     * @Route("/manage_aliments", name="manage_aliments")
     */
    public function manage_aliments(Request $request) {
        // find all aliment categories  
        $allAlimentCategories = $this->getDoctrine()->getRepository(AlimentCategory::class)->findAllOrderedByDescription();
        if (!$allAlimentCategories) {
            throw $this->createNotFoundException(
                'No aliment category found'
            );
        }
        $alimentCategory = new AlimentCategory();
        // var_dump($options['allAlimentCategories']); echo("<br />"); 
        // build form 
        $data = [
            'aliment_category' => $alimentCategory
        ]; 
        $formBuilder = $this->createFormBuilder($data); 
        $formBuilder 
        ->add('aliment_category', AlimentCategoryType::class, array ( 
            'allAlimentCategories' => $allAlimentCategories, 
            'label' => false 
        )); 
        $form = $formBuilder->getForm();
        // var_dump(array_keys($form->all())); 
        // handle POST request 
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('aliment_category')->get('add')->isClicked()) {
                $alimentCategory = $form->get('aliment_category')->getData();
                $alimentCategory->setGFib(0); 
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($alimentCategory);
                $entityManager->flush();
                return $this->redirectToRoute('index');
            } else if ($form->get('aliment_category')->get('delete')->isClicked()) {
                $entityManager = $this->getDoctrine()->getManager();
                foreach ($form->get('aliment_category')->get('selectedAlimentCategories')->getData() as $AlimentCategoryToDeleteId) {
                    $alimentCategoryToDelete = $this->getDoctrine()->getRepository(AlimentCategory::class)->find($AlimentCategoryToDeleteId);
                    $entityManager->remove($alimentCategoryToDelete);
                    $entityManager->flush();
                }
                return $this->redirectToRoute('index');
            } else if ($form->get('aliment_category')->get('cancel')->isClicked()) {
                return $this->redirectToRoute('index');
            }    
        }
        return $this->render('aliment_category/manage.html.twig', [
            'form' => $form->createView(), 
            'all_aliments' => $allAlimentCategories,
            'aliment_category_number' => count($allAlimentCategories)
        ]);
    }
}