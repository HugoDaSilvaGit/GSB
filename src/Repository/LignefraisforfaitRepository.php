<?php

namespace App\Repository;

use App\Entity\LigneFraisForfait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * @method LigneFraisForfait|null find($id, $lockMode = null, $lockVersion = null)
 * @method LigneFraisForfait|null findOneBy(array $criteria, array $orderBy = null)
 * @method LigneFraisForfait[]    findAll()
 * @method LigneFraisForfait[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LignefraisforfaitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LigneFraisForfait::class);
    }

    public function findFraisForfaitsByIdFicheFrais($idfichefrais):array
    {
        $rsm = new ResultSetMapping();
        $rsm->addEntityResult('App\Entity\Lignefraisforfait', 'lff');
        $rsm->addScalarResult('idfraisforfait', 'idfraisforfait');
        $rsm->addScalarResult('quantite', 'quantite');
        $rsm->addScalarResult('montanttotaldufraisforfait', 'montanttotaldufraisforfait');

        $sql="SELECT lff.idfraisforfait as idfraisforfait, lff.quantite as quantite, lff.quantite*frf.montantfraisforfait as montanttotaldufraisforfait
            FROM Lignefraisforfait AS lff
            INNER JOIN Fraisforfait AS frf ON lff.idfraisforfait = frf.idfraisforfait
            WHERE lff.idfichefrais=?";

        return $selectFrais = $this->_em->createNativeQuery($sql, $rsm)
        ->setParameter(1, $idfichefrais)
        ->getResult();

        $fichesfrais = json_decode( json_encode($selectFrais), false);

    }

    // /**
    //  * @return LigneFraisForfait[] Returns an array of LigneFraisForfait objects
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
    public function findOneBySomeField($value): ?LigneFraisForfait
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
