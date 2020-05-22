<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findUserWithLogin($login) {
        $output = []; 
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
        SELECT u.id, u.type, u.login, u.password 
        FROM user u   
        WHERE u.login = :loginValue
        ORDER BY u.id ASC 
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'loginValue' => $login
        ]);
        $foundUsers = $stmt->fetchAll();
        foreach ($foundUsers as $user) { 
            $output = $user;
            break; 
        }
        return $output;
    }
}
