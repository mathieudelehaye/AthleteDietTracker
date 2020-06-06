<?php

namespace App\Repository;

use App\Entity\Aliment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Aliment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Aliment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Aliment[]    findAll()
 * @method Aliment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlimentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Aliment::class);
    }

    public function findEverydayMealAliments($athlete_id, $meal_name) {
        $output = []; 
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
        SELECT a.quantity, a.aliment_category_id
        FROM day d, meal m, aliment a 
        WHERE m.id = a.meal_id AND d.id = m.day_id AND d.athlete_id = :athleteId AND m.name = :mealName AND d.name = "everyday"
        ORDER BY a.position ASC 
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'athleteId' => $athlete_id, 
            'mealName' => $meal_name
        ]);
        $alimentsWithCategoryIndexedTab = $stmt->fetchAll();
        foreach ($alimentsWithCategoryIndexedTab as $aliment) { 
            $output[$aliment["aliment_category_id"]] = $aliment["quantity"];
        }
        return $output;
    }
}
