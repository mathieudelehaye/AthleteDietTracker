<?php

namespace App\Repository;

use App\Entity\AlimentCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AlimentCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method AlimentCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method AlimentCategory[]    findAll()
 * @method AlimentCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlimentCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AlimentCategory::class);
    }

    public  function findAllOrderedByDescription() {
        $outputTab = $this->findAll();        
        usort($outputTab, function ($item1, $item2) {            
            $retValue = -2; 
            if($item1->getDescription()==$item2->getDescription()) $retValue = 0;
            elseif($item1->getDescription()>$item2->getDescription()) $retValue =  1;
            else $retValue =  -1;
            return $retValue; 
        }); 
        return $outputTab; 
    }
}
