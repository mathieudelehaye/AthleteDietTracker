<?php
namespace App\Controller;
use App\Entity\User;
use App\Entity\Athlete;
use App\Entity\Day;
use App\Entity\Meal;
use App\Entity\Aliment;
use App\Entity\AlimentCategory;
use App\Form\UserType;
use App\Form\AthleteType;
use App\Form\DayType;
use App\Form\MealType;
use App\Form\AlimentType;
use ArrayObject;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

define("DEBUG",0); 

class DefaultController extends Controller {
	/**
     * @Route("/", name="index")
     */
    public function index() {
        
        //echo("DefaultController.php: index()");
        
        return $this->redirectToRoute('signin');
    }
    /**
     * @Route("/success", name="task_success")
     */
    public function success() {
        return $this->render('default/success.html.twig');
    }
    /**
     * @Route("/signin", name="signin")
     */
    public function signIn(Request $request) {
        // Login removed: auto log in 
        $foundUser = $this->getDoctrine()->getRepository(User::class)->findUserWithLogin('md');
        $this->get('session')->set('loginUserId', $foundUser["id"]);
        if($this->get('session')->has('loginUserId')) { 
            return $this->redirectToRoute('manage', array(
                'athlete_id' => 0, 
                'day_id' => 0, 
                'meal_id' => 0 
            )); 
        }
        $userToEnter = new User();
        $data = [
            'user' => $userToEnter
        ]; 
        $formBuilder = $this->createFormBuilder($data); 
        $formBuilder
        ->add('user', UserType::class, array ( 
            'label' => false 
        ));
        $form = $formBuilder->getForm();
        // handle POST request 
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $foundUser = $this->getDoctrine()->getRepository(User::class)->findUserWithLogin($form->get('user')->get('login')->getData());
            if (!$foundUser) {
                throw $this->createNotFoundException(
                    'No user found for entered login'
                );
            }
            if($form->get('user')->get('password')->getData()==$foundUser["password"]) {
                $this->get('session')->set('loginUserId', $foundUser["id"]);
                return $this->redirectToRoute('manage', array(
                    'athlete_id' => 0, 
                    'day_id' => 0, 
                    'meal_id' => 0 
                )); 
            }
        }
        // render page 
        return $this->render('default/login.html.twig', array ( 
            'form' => $form->createView()
        ));
    }
    /**
     * @Route("/athlete/{athlete_id}/day/{day_id}/meal/{meal_id}", name="manage")
     */
    public function manage(Request $request, $athlete_id, $day_id, $meal_id) {
        // check user authentication 
        if(!$this->get('session')->has('loginUserId')) {
            return $this->redirectToRoute('signin'); 
        } else {
            $user_id = $this->get('session')->get('loginUserId'); 
        }
        // find linked entities (athlete, day, meal, aliments) 
        if($athlete_id != 0) {
            $athlete = $this->getDoctrine()->getRepository(Athlete::class)->find($athlete_id);
        } else {
            $athlete = $this->getDoctrine()->getRepository(Athlete::class)->findFirstPositionForUserId($user_id);
            if ($athlete) {
                $athlete_id = $athlete->getId(); 
            }
        }
        // echo("<br/>"); var_dump($athlete); 
        // find all aliment categories  
        $allAlimentCategories = $this->getDoctrine()->getRepository(AlimentCategory::class)->findAllOrderedByDescription();
        if (!$allAlimentCategories) {
            throw $this->createNotFoundException(
                'No aliment category found'
            );
        }
        // echo("<br/>"); var_dump($allAlimentCategories); 
        // find user athletes 
        $userAthletes = $this->getDoctrine()->getRepository(Athlete::class)->findBy(
            [ 'user_id' => $user_id ], 
            [ 'position' => 'ASC']
        );
        // var_dump($userAthletes); 
        // find athlete days 
        $athleteDays = $this->getDoctrine()->getRepository(Day::class)->findBy(
            [ 'athlete_id' => $athlete_id ], 
            [ 'position' => 'ASC']
        );
        // echo("<br/>"); var_dump($athleteDays); 
        // day and meal positions to display as the selected tabs.  If id is zero, then take the first position item 
        $dayPosToDisplay = 1;
        if($day_id == 0) {
            $firstAthleteDay = $this->getDoctrine()->getRepository(Day::class)->findFirstPositionForAthleteId($athlete_id); 
            if($firstAthleteDay != NULL) {
                $day_id = $firstAthleteDay->getId();
            }

        }
        $mealPosToDisplay = 1;
        if($meal_id == 0) {
            $firstDayMeal = $this->getDoctrine()->getRepository(Meal::class)->findFirstPositionForDayId($day_id); 
            if($firstDayMeal != NULL) {
                $meal_id = $firstDayMeal->getId(); 
            }
        }
        // find athlete meals 
        $athleteMeals = []; 
        $athleteAliments = []; 
        foreach ($athleteDays as $dayIndex => $dayObject) {
            if($dayObject->getId() == $day_id) {
                $dayPosToDisplay = $dayIndex + 1; 
            }
            $athleteMeals[$dayIndex] = $this->getDoctrine()->getRepository(Meal::class)->findBy(
                [ 'day_id' => $dayObject->getId() ], 
                [ 'position' => 'ASC']
            );
            // echo("<br/>"); var_dump($athleteMeals[$dayIndex]); 
            // find athlete aliments 
            foreach ($athleteMeals[$dayIndex] as $mealIndex => $mealObject) {
                if($mealObject->getId() == $meal_id) {
                    $mealPosToDisplay = $mealIndex + 1; 
                }
                $athleteAliments[$dayIndex][$mealIndex] = $this->getDoctrine()->getRepository(Aliment::class)->findBy(
                    [ 'meal_id' => $mealObject->getId() ], 
                    [ 'position' => 'ASC']
                ); 
                // echo("<br/>"); var_dump($athleteAliments[$dayIndex][$mealIndex]); 
            }
        }
        // build form 
        $data = [
            'athlete' => $athlete
        ]; 
        foreach ($athleteDays as $dayIndex => $dayObject) {
            $data['day_'.($dayIndex)] = $dayObject; 
            foreach ($athleteMeals[$dayIndex] as $mealIndex => $mealObject) {
                $data['day_'.($dayIndex).'_meal_'.($mealIndex)] = $mealObject;
                foreach ($athleteAliments[$dayIndex][$mealIndex] as $alimentIndex => $alimentObject) { 
                    $data['day_'.($dayIndex).'_meal_'.($mealIndex).'_aliment_'.($alimentIndex)] = $alimentObject;
                }
            }
        }
        // var_dump($data); 
        $formBuilder = $this->createFormBuilder($data); 
        if(count($userAthletes)) {
            $formBuilder
            ->add('athlete', AthleteType::class, array ( 
                'userAthletes' => $userAthletes, 
                'athlete_id' => $athlete_id, 
                'label' => false 
            ));
        }
        // }
        // add days, meals and aliments  
        foreach ($athleteDays as $dayIndex => $dayObject) {
            $formBuilder->add('day_'.($dayIndex), DayType::class, array ( 
                'label' => false 
            ));
            foreach ($athleteMeals[$dayIndex] as $mealIndex => $mealObject) {
                $formBuilder->add('day_'.($dayIndex).'_meal_'.($mealIndex),MealType::class, array ( 
                    'label' => false 
                ));
                foreach ($athleteAliments[$dayIndex][$mealIndex] as $alimentIndex => $alimentObject) { 
                    $formBuilder->add('day_'.($dayIndex).'_meal_'.($mealIndex).'_aliment_'.($alimentIndex), AlimentType::class, array ( 
                        'allAlimentCategories'=> $allAlimentCategories,  
                        'label' => false 
                    )); 
                }
            }
        }
        $formBuilder
        ->add('save', SubmitType::class, array(
            'label' => 'SAUVER'
        ))
        ->add('logout', SubmitType::class, array(
            'label' => 'Sign Out'
        )); 
        $form = $formBuilder->getForm();
        // var_dump(array_keys($form->all())); 
        // handle POST request 
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('athlete')->get('select')->isClicked()) {
                var_dump($form->get('athlete')->get('selectedAthlete')->getData()); 
                return $this->redirectToRoute('manage', array(
                    'athlete_id' => $form->get('athlete')->get('selectedAthlete')->getData(), 
                    'day_id' => 0, 
                    'meal_id' => 0
                ));  
            }
            if ($form->get('athlete')->get('add_day')->isClicked()) { 
                $newDay = new Day();
                $newDay->setName("lundi");
                $newDay->setEquivalentName("aucun");
                $newDay->setAthleteId($athlete_id);
                $newDay->setPosition(count($athleteDays)+1);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($newDay);
                $entityManager->flush();
                return $this->redirectToRoute('manage', array(
                    'athlete_id' => $athlete_id, 
                    'day_id' => $newDay->getId(), 
                    'meal_id' => 0
                )); 
            }
            foreach ($athleteDays as $dayIndex => $dayObject) {
                if ($form->get('day_'.$dayIndex)!=NULL&$form->get('day_'.$dayIndex)->get('delete')->isClicked()) { 
                    $this->deleteItemAmong($dayObject, $athleteDays); 
                    $this->getDoctrine()->getManager()->flush();
                    return $this->redirectToRoute('manage', array(
                        'athlete_id' => $athlete_id, 
                        'day_id' => 0, 
                        'meal_id' => 0
                    )); 
                }
                if ($form->get('day_'.$dayIndex)!=NULL&$form->get('day_'.$dayIndex)->get('add_meal')->isClicked()) { 
                    $newMeal = new Meal();
                    $newMeal->setDayId($day_id);
                    $newMeal->setPosition(count($athleteMeals[$dayIndex])+1);
                    $newMeal->setName("dejeuner"); 
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($newMeal);
                    $entityManager->flush();
                    return $this->redirectToRoute('manage', array(
                        'athlete_id' => $athlete_id, 
                        'day_id' => $day_id, 
                        'meal_id' => $newMeal->getId()
                    )); 
                }
                foreach ($athleteMeals[$dayIndex] as $mealIndex => $mealObject) {
                    if ($form->get('day_'.$dayIndex.'_meal_'.$mealIndex)!=NULL&$form->get('day_'.$dayIndex.'_meal_'.$mealIndex)->get('delete')->isClicked()) { 
                        $this->deleteItemAmong($mealObject, $athleteMeals[$dayIndex]); 
                        $this->getDoctrine()->getManager()->flush();
                        return $this->redirectToRoute('manage', array(
                            'athlete_id' => $athlete_id, 
                            'day_id' => $day_id, 
                            'meal_id' => 0
                        )); 
                    } 
                    if ($form->get('day_'.$dayIndex.'_meal_'.$mealIndex)!=NULL&$form->get('day_'.$dayIndex.'_meal_'.$mealIndex)->get('add_aliment')->isClicked()) { 
                        $newAliment = new Aliment();
                        $newAliment->setMealId($meal_id);
                        $newAliment->setPosition(count($athleteAliments[$dayIndex][$mealIndex])+1);
                        // echo("<br/>\$allAlimentCategories[0]->getId()=".$allAlimentCategories[0]->getId()); 
                        $newAliment->setAlimentCategoryId($allAlimentCategories[0]->getId());  
                        $newAliment->setQuantity(1);
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($newAliment);
                        $entityManager->flush();
                        return $this->redirectToRoute('manage', array(
                            'athlete_id' => $athlete_id, 
                            'day_id' => $day_id, 
                            'meal_id' => $meal_id
                        )); 
                    }
                    foreach ($athleteAliments[$dayIndex][$mealIndex] as $alimentIndex => $alimentObject) { 
                        if($form->get('day_'.$dayIndex.'_meal_'.$mealIndex.'_aliment_'.$alimentIndex)->get('delete')->isClicked()) {
                            $this->deleteItemAmong($alimentObject, $athleteAliments[$dayIndex][$mealIndex]); 
                            $this->getDoctrine()->getManager()->flush();
                            return $this->redirectToRoute('manage', array(
                                'athlete_id' => $athlete_id, 
                                'day_id' => $day_id, 
                                'meal_id' => $meal_id
                            )); 
                        }  
                    }
                }
            }
            if($form->get('save')->isClicked()) { 
                // echo("\$athlete_id=".$athlete_id.", \$day_id=".$day_id.", \$meal_id=".$meal_id); 
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($athlete);
                foreach ($athleteDays as $dayIndex => $dayObject) {
                    $entityManager->persist($dayObject);
                    foreach ($athleteMeals[$dayIndex] as $mealIndex => $mealObject) {
                        $entityManager->persist($mealObject);
                        foreach ($athleteAliments[$dayIndex][$mealIndex] as $alimentIndex => $alimentObject) { 
                            $entityManager->persist($alimentObject);                
                        }
                    }
                }
                $entityManager->flush();
                return $this->redirectToRoute('manage', array(
                    'athlete_id' => $athlete_id, 
                    'day_id' => $day_id, 
                    'meal_id' => $meal_id
                )); 
            }    
            if($form->get('logout')->isClicked()) { 
                $this->get('session')->remove('loginUserId'); 
                return $this->redirectToRoute('signin'); 
            }    
        }
        // convert table from $allAlimentCategories (Doctrine) to $alimentCategoryWithId (x: aliment_category_id, y: {g_pro, g_hyd, g_fat})
        $alimentCategoryWithId = []; 
        foreach($allAlimentCategories as $alimentCategory) {
            $alimentCategoryWithId[$alimentCategory->getId()]['description'] = $alimentCategory->getDescription();
            $alimentCategoryWithId[$alimentCategory->getId()]['g_pro'] = $alimentCategory->getGPro();
            $alimentCategoryWithId[$alimentCategory->getId()]['g_hyd'] = $alimentCategory->getGHyd();
            $alimentCategoryWithId[$alimentCategory->getId()]['g_fat'] = $alimentCategory->getGFat();
            $alimentCategoryWithId[$alimentCategory->getId()]['ene'] = $alimentCategory->getEne();
        }
        // compute macros with everyday 
        $macros = []; 
        $everydayAlimentsToDisplay = []; 
        $this->computeMacrosWithEveryday($athlete, $athleteDays, $athleteMeals, $athleteAliments, $alimentCategoryWithId, $macros, $everydayAlimentsToDisplay); 
        // render page 
        $parameters = [
            'form' => $form->createView(), 
            'athlete_days' => $athleteDays, 
            'athlete_meals' => $athleteMeals, 
            'athlete_aliments' => $athleteAliments,
            'day_position_to_display' => $dayPosToDisplay, 
            'meal_position_to_display' => $mealPosToDisplay, 
            'aliment_category_with_id' => $alimentCategoryWithId, 
            'macros' => $macros,  
            'everydayAlimentsToDisplay' => $everydayAlimentsToDisplay
        ];
        return $this->render('default/manage.html.twig', $parameters);
    }
    private function computeMacrosWithEveryday($athlete, $athleteDays, $athleteMeals, $athleteAliments, $alimentCategoryWithId, &$macros, &$everydayAlimentsToDisplay) {
        $macros = []; $everydayAlimentsToDisplay = []; 
        $weekMacros = []; $dayMacros = []; $mealMacros = []; $alimentMacros = []; 
        $weekMacros["ene"] = 0; 
        foreach ($athleteDays as $dayIndex => $dayObject) {
            $dayMacros[$dayIndex]["g_pro"] = 0; $dayMacros[$dayIndex]["g_hyd"] = 0; $dayMacros[$dayIndex]["g_fat"] = 0; $dayMacros[$dayIndex]["ene"] = 0; 
            foreach ($athleteMeals[$dayIndex] as $mealIndex => $mealObject) {
                if($dayObject->getName()!="everyday") {
                    $mealEverydayAliments = $this->getDoctrine()->getRepository(Aliment::class)->findEverydayMealAliments($athlete->getId(), $mealObject->getName()); 
                    $objMealEverydayAliments = new ArrayObject($mealEverydayAliments); 
                }
                $mealMacros[$dayIndex][$mealIndex]["g_pro"] = 0; $mealMacros[$dayIndex][$mealIndex]["g_hyd"] = 0; $mealMacros[$dayIndex][$mealIndex]["g_fat"] = 0; $mealMacros[$dayIndex][$mealIndex]["ene"] = 0; 
                foreach ($athleteAliments[$dayIndex][$mealIndex] as $alimentIndex => $alimentObject) {
                    $alimentMacros[$dayIndex][$mealIndex][$alimentIndex] = []; 
                    $alimentMacros[$dayIndex][$mealIndex][$alimentIndex]["g_pro"]= $alimentCategoryWithId[$alimentObject->getAlimentCategoryId()]["g_pro"]*$alimentObject->getQuantity()/100; 
                    $alimentMacros[$dayIndex][$mealIndex][$alimentIndex]["g_hyd"]= $alimentCategoryWithId[$alimentObject->getAlimentCategoryId()]["g_hyd"]*$alimentObject->getQuantity()/100; 
                    $alimentMacros[$dayIndex][$mealIndex][$alimentIndex]["g_fat"]= $alimentCategoryWithId[$alimentObject->getAlimentCategoryId()]["g_fat"]*$alimentObject->getQuantity()/100; 
                    $alimentMacros[$dayIndex][$mealIndex][$alimentIndex]["g_tot"]= $alimentObject->getQuantity(); 
                    $alimentMacros[$dayIndex][$mealIndex][$alimentIndex]["ene"]= $alimentCategoryWithId[$alimentObject->getAlimentCategoryId()]["ene"]*$alimentObject->getQuantity()/100;
                    if($dayObject->getName()!="everyday" && array_key_exists($alimentObject->getAlimentCategoryId(), $mealEverydayAliments)) {
                        $alimentMacros[$dayIndex][$mealIndex][$alimentIndex]["g_pro"]+= $alimentCategoryWithId[$alimentObject->getAlimentCategoryId()]["g_pro"]*$mealEverydayAliments[$alimentObject->getAlimentCategoryId()]/100; 
                        $alimentMacros[$dayIndex][$mealIndex][$alimentIndex]["g_hyd"]+= $alimentCategoryWithId[$alimentObject->getAlimentCategoryId()]["g_hyd"]*$mealEverydayAliments[$alimentObject->getAlimentCategoryId()]/100; 
                        $alimentMacros[$dayIndex][$mealIndex][$alimentIndex]["g_fat"]+= $alimentCategoryWithId[$alimentObject->getAlimentCategoryId()]["g_fat"]*$mealEverydayAliments[$alimentObject->getAlimentCategoryId()]/100; 
                        $alimentMacros[$dayIndex][$mealIndex][$alimentIndex]["g_tot"]+= $mealEverydayAliments[$alimentObject->getAlimentCategoryId()]; 
                        $alimentMacros[$dayIndex][$mealIndex][$alimentIndex]["ene"]+= $alimentCategoryWithId[$alimentObject->getAlimentCategoryId()]["ene"]*$mealEverydayAliments[$alimentObject->getAlimentCategoryId()]/100;      
                        // everyday aliment is removed after used   
                        $objMealEverydayAliments->offsetUnset($alimentObject->getAlimentCategoryId());
                    }
                    $mealMacros[$dayIndex][$mealIndex]["g_pro"]+=$alimentMacros[$dayIndex][$mealIndex][$alimentIndex]["g_pro"]; 
                    $mealMacros[$dayIndex][$mealIndex]["g_hyd"]+=$alimentMacros[$dayIndex][$mealIndex][$alimentIndex]["g_hyd"]; 
                    $mealMacros[$dayIndex][$mealIndex]["g_fat"]+=$alimentMacros[$dayIndex][$mealIndex][$alimentIndex]["g_fat"]; 
                    $mealMacros[$dayIndex][$mealIndex]["ene"]+=$alimentMacros[$dayIndex][$mealIndex][$alimentIndex]["ene"]; 
                }
                if($dayObject->getName()!="everyday") {
                    $otherEverydayAliments = $objMealEverydayAliments->getArrayCopy(); 
                    // echo("<br/><br/>\$dayIndex: ".$dayIndex.", \$mealIndex: ".$mealIndex.", \$otherEverydayAliments: "); var_dump($otherEverydayAliments);
                    $alimentIndex = 0;  
                    foreach ($otherEverydayAliments as $alimentCategoryId => $alimentQuantity) {
                        $everydayAlimentsToDisplay[$dayIndex][$mealIndex][$alimentIndex]["description"]= $alimentCategoryWithId[$alimentCategoryId]["description"]; 
                        $everydayAlimentsToDisplay[$dayIndex][$mealIndex][$alimentIndex]["g_pro"]= $alimentCategoryWithId[$alimentCategoryId]["g_pro"]*$alimentQuantity/100; 
                        $everydayAlimentsToDisplay[$dayIndex][$mealIndex][$alimentIndex]["g_hyd"]= $alimentCategoryWithId[$alimentCategoryId]["g_hyd"]*$alimentQuantity/100; 
                        $everydayAlimentsToDisplay[$dayIndex][$mealIndex][$alimentIndex]["g_fat"]= $alimentCategoryWithId[$alimentCategoryId]["g_fat"]*$alimentQuantity/100; 
                        $everydayAlimentsToDisplay[$dayIndex][$mealIndex][$alimentIndex]["g_tot"]= $alimentQuantity; 
                        $everydayAlimentsToDisplay[$dayIndex][$mealIndex][$alimentIndex]["ene"]= $alimentCategoryWithId[$alimentCategoryId]["ene"]*$alimentQuantity/100; 
                        $mealMacros[$dayIndex][$mealIndex]["g_pro"]+=$everydayAlimentsToDisplay[$dayIndex][$mealIndex][$alimentIndex]["g_pro"]; 
                        $mealMacros[$dayIndex][$mealIndex]["g_hyd"]+=$everydayAlimentsToDisplay[$dayIndex][$mealIndex][$alimentIndex]["g_hyd"]; 
                        $mealMacros[$dayIndex][$mealIndex]["g_fat"]+=$everydayAlimentsToDisplay[$dayIndex][$mealIndex][$alimentIndex]["g_fat"]; 
                        $mealMacros[$dayIndex][$mealIndex]["ene"]+=$everydayAlimentsToDisplay[$dayIndex][$mealIndex][$alimentIndex]["ene"]; 
                        $alimentIndex++; 
                    }
                    // echo(", \$everydayAlimentsToDisplay: "); var_dump($everydayAlimentsToDisplay); 
                }
                $dayMacros[$dayIndex]["g_pro"] += $mealMacros[$dayIndex][$mealIndex]["g_pro"];                  
                $dayMacros[$dayIndex]["g_hyd"] += $mealMacros[$dayIndex][$mealIndex]["g_hyd"];                  
                $dayMacros[$dayIndex]["g_fat"] += $mealMacros[$dayIndex][$mealIndex]["g_fat"];                  
                $dayMacros[$dayIndex]["ene"] += $mealMacros[$dayIndex][$mealIndex]["ene"];                
            }              
            $dayMacros[$dayIndex]["g_pro_per_kg"]=$dayMacros[$dayIndex]["g_pro"] / $athlete->getWeight();
            $dayMacros[$dayIndex]["g_hyd_per_kg"]=$dayMacros[$dayIndex]["g_hyd"] / $athlete->getWeight();
            $dayMacros[$dayIndex]["g_fat_per_kg"]=$dayMacros[$dayIndex]["g_fat"] / $athlete->getWeight();   
            if($dayObject->getName()!="everyday") {
                $weekMacros["ene"] += $dayMacros[$dayIndex]["ene"]; 
            }             
        }
        $macros['week'] = $weekMacros; $macros['days'] = $dayMacros; $macros['meals'] = $mealMacros; $macros['aliments'] = $alimentMacros; 
    }
    public function deleteItemAmong($itemToDelete, $allItems) {
        // remove an item among a set of ones 
        $this->getDoctrine()->getManager()->remove($itemToDelete);
        $mustDeleteSubItems = false; 
        switch(get_class($itemToDelete)) {
            case "App\Entity\Day":
            $subItemType = Meal::class; $subItemSelectKey = 'day_id'; $mustDeleteSubItems = true; 
            break; 
            case "App\Entity\Meal":
            $subItemType = Aliment::class; $subItemSelectKey = 'meal_id'; $mustDeleteSubItems = true; 
            break; 
        }
        if($mustDeleteSubItems) { 
            $subItems = $this->getDoctrine()->getRepository($subItemType)->findBy(
                [$subItemSelectKey => $itemToDelete->getId()]
            );
            $this->deleteAllItems($subItems); 
        }
        // re-order other items 
        foreach ($allItems as $item) {
            if($item->getPosition()>$itemToDelete->getPosition()) {
                $item->setPosition($item->getPosition()-1); 
            } 
        }
    }
    private function deleteAllItems($items) {
        // remove all items
        foreach ($items as $item) {
            $this->getDoctrine()->getManager()->remove($item);
            $mustDeleteSubItems = false; 
            switch(get_class($item)) {
                case "App\Entity\Meal":
                $subItemType = Aliment::class; $subItemSelectKey = 'meal_id'; $mustDeleteSubItems = true; 
                break; 
                case "App\Entity\Aliment":
                return; 
            }
            if($mustDeleteSubItems) { 
                $subItems = $this->getDoctrine()->getRepository($subItemType)->findBy(
                    [$subItemSelectKey => $item->getId()]
                );
                $this->deleteAllItems($subItems); 
            }
        }
    }
}
