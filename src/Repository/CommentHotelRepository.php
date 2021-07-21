<?php

namespace App\Repository;

use App\Entity\CommentHotel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommentHotel|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentHotel|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentHotel[]    findAll()
 * @method CommentHotel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentHotelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommentHotel::class);
    }

    // /**
    //  * @return CommentHotel[] Returns an array of CommentHotel objects
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
    public function findOneBySomeField($value): ?CommentHotel
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
