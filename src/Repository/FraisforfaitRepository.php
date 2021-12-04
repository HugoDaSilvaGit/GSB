<?php

namespace App\Repository;

use App\Entity\Fraisforfait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Fraisforfait|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fraisforfait|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fraisforfait[]    findAll()
 * @method Fraisforfait[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FraisforfaitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fraisforfait::class);
    }

    // /**
    //  * @return Fraisforfait[] Returns an array of Fraisforfait objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Fraisforfait
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
