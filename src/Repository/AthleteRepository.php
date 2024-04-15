<?php

namespace App\Repository;

use App\Entity\Athlete;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Athlete|null find($id, $lockMode = null, $lockVersion = null)
 * @method Athlete|null findOneBy(array $criteria, array $orderBy = null)
 * @method Athlete[]    findAll()
 * @method Athlete[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AthleteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Athlete::class);
    }

    public function findFirstPositionForUserId($user_id) {
        $output = $this->findBy([
            'user_id' => $user_id,  
            'position' => 1 
        ]);
        if (count($output) != 0) {
            return $output[0]; 
        } else {
            return NULL; 
        }
    }
}
