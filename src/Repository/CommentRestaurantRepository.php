<?php

namespace App\Repository;

use App\Entity\CommentRestaurant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommentRestaurant|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentRestaurant|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentRestaurant[]    findAll()
 * @method CommentRestaurant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRestaurantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommentRestaurant::class);
    }

    // /**
    //  * @return CommentRestaurant[] Returns an array of CommentRestaurant objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CommentRestaurant
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
