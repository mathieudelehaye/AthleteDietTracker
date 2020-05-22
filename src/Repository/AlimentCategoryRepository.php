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
            // echo("<br/>usortFunction: \$item1->getDescription()=".$item1->getDescription().", \$item2->getDescription()=".$item2->getDescription().", \$retValue=".$retValue); 
            return $retValue; 
        }); 
        return $outputTab; 
    }
//    /**
//     * @return AlimentCategory[] Returns an array of AlimentCategory objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AlimentCategory
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
