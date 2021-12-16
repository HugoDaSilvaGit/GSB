<?php

namespace App\Repository;

use Doctrine\ORM\Query\Expr\Join;
use App\Entity\Fichefrais;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\ORM\Query\ResultSetMapping;


/**
 * @method Fichefrais|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fichefrais|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fichefrais[]    findAll()
 * @method Fichefrais[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FichefraisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fichefrais::class);
    }

    public function findFicheFraisByIdVisiteurAndAnnee($idvisiteur, $annee): array
    {
        $rsm = new ResultSetMapping();
        $rsm->addEntityResult('App\Entity\FicheFrais', 'fif');
        $rsm->addScalarResult('number', 'number');
        $rsm->addScalarResult('idfichefrais', 'idfichefrais');
        $rsm->addScalarResult('date', 'date');
        $rsm->addScalarResult('nbjustificatifs', 'nbjustificatifs');
        $rsm->addScalarResult('montantvalide', 'montantvalide');
        $rsm->addScalarResult('datemodif', 'datemodif');
        $rsm->addScalarResult('idvisiteur', 'idvisiteur');
        $rsm->addScalarResult('idetat', 'idetat');
        $rsm->addScalarResult('montantTotalFraisForfait', 'montantTotalFraisForfait');
        $rsm->addScalarResult('montantTotalFraisHorsForfait', 'montantTotalFraisHorsForfait');
         
        $sql= ' SELECT DISTINCT ROW_NUMBER() OVER (ORDER BY fif.idfichefrais) AS number, fif.idfichefrais AS idfichefrais , fif.date AS date, fif.nbjustificatifs AS nbjustificatifs, fif.montantvalide AS montantvalide, fif.datemodif AS datemodif, fif.idetat AS idetat, fif.idvisiteur AS idvisiteur, montantTotalFraisForfait, montantTotalFraisHorsForfait
            FROM FicheFrais fif
            INNER JOIN (SELECT lff.idfichefrais, SUM(frf.montantFraisForfait*lff.quantite) AS montantTotalFraisForfait
                        FROM LigneFraisForfait AS lff
                        INNER JOIN FraisForfait AS frf ON frf.idfraisforfait = lff.idfraisforfait
                        GROUP BY lff.idfichefrais) a USING (idfichefrais)
            INNER JOIN (SELECT e.idetat
                        FROM Etat AS e) b USING (idetat)
            LEFT JOIN (SELECT lfhf.idfichefrais, SUM(lfhf.montant) AS montantTotalFraisHorsForfait, lfhf.libelle AS libelleFraisHorsForfait
                        FROM LigneFraisHorsForfait AS lfhf
                        GROUP BY lfhf.idfichefrais) c USING (idfichefrais)
            WHERE fif.idvisiteur =?
            AND YEAR(fif.date) =?
            ORDER BY MONTH(fif.date) DESC';
         
        return $selectFicheFrais = $this->_em->createNativeQuery($sql, $rsm)
        ->setParameter(1, $idvisiteur)
        ->setParameter(2, $annee)
        ->getResult();

        $selectFicheFrais = json_decode( json_encode($selectFicheFrais), false);
    }


    /*public function findFicheFraisByIdVisiteurAndAnnee($idvisiteur, $annee): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $a = $this->getEntityManager()->createQueryBuilder();
        $b = $this->getEntityManager()->createQueryBuilder();
        $c = $this->getEntityManager()->createQueryBuilder();

        $selectFraisForfait = $a->select(array('lff.idfichefrais, SUM(frf.montantFraisForfait*lff.quantite) AS montantTotalFraisForfait'))
                            ->from('App:Lignefraisforfait', 'lff')
                            ->innerJoin('App:Fraisforfait', 'frf', Join::WITH, 'frf.idfraisforfait = lff.idfraisforfait')
                            ->groupBy('lff.idfichefrais');

        $selectEtat = $b->select(array('e.idetat'))
                            ->from('App:Etat', 'e');
        
        $selectFraisHorsForfait = $c->select(array('lfhf.idfichefrais, SUM(lfhf.montant) AS montantTotalFraisHorsForfait'))
                            ->from('App:Lignefraishorsforfait','lfhf')
                            ->groupBy('lfhf.idfichefrais');
        
        return $FicheFraisByIdVisiteurAndAnnee = $qb
            ->select(array('fif.idfichefrais, identity(fif.idvisiteur) AS idvisiteur, fif.date, fif.nbjustificatifs, fif.montantvalide, fif.datemodif, identity(fif.idetat) AS idetat, montantTotalFraisForfait, montantTotalFraisHorsForfait'))
            ->from('App:Fichefrais', 'fif')
            ->innerJoin('('.$selectFraisForfait.')', 'electfraisforfait',Join::WITH, 'fif.idfichefrais = selectfraisforfait.idfichesfrais')
            ->innerJoin('('.$selectEtat.')','selectetat', Join::WITH, 'fif.idetat = selectetat.idetat')
            ->leftJoin('('.$selectFraisHorsForfait.')', 'selectFraisHorsForfait', Join::WITH, 'fif.idfichefrais = selectfraishorsforfait.idfichefrais')
            ->where('fif.idvisiteur = :idvisiteur')
            ->setParameter('idvisiteur' , $idvisiteur)
            ->andWhere('YEAR(fif.date) = :annee')
            ->setParameter('annee', $annee)
            ->groupBy('fif.idfichefrais')
            ->orderBy('MONTH(fif.date)', 'ASC')
            ->setMaxResults(12)
            ->getQuery()
            ->getResult()
        ;
    }*/

    /*public function findFicheFraisByIdVisiteurAndAnnee($idvisiteur, $annee): array
    {
        return $query = $this
        ->getEntityManager()
        ->createQuery('SELECT DISTINCT fif.idfichefrais, fif.idvisiteur, fif.date, fif.nbjustificatifs, fif.montantvalide, fif.datemodif, fif.idetat, montantTotalFraisForfait, montantTotalFraisHorsForfait
        FROM App:FicheFrais fif
        INNER JOIN (SELECT lff.idfichefrais, SUM(frf.montantFraisForfait*lff.quantite) AS montantTotalFraisForfait
                    FROM LigneFraisForfait AS lff
                    INNER JOIN FraisForfait AS frf ON frf.idfraisforfait = lff.idfraisforfait
                    GROUP BY lff.idfichefrais) a USING (idfichefrais)
        INNER JOIN (SELECT e.idetat
                    FROM Etat AS e) b USING (idetat)
        LEFT JOIN (SELECT lfhf.idfichefrais, SUM(lfhf.montant) AS montantTotalFraisHorsForfait, lfhf.libelle AS libelleFraisHorsForfait
                    FROM LigneFraisHorsForfait AS lfhf
                    GROUP BY lfhf.idfichefrais) c USING (idfichefrais)
        WHERE fif.idvisiteur =?
        AND YEAR(fif.date) =?
        ORDER BY MONTH(fif.date) ASC')
        ->setParameter(1, $idvisiteur)
        ->setParameter(2, $annee)
        ->getResult()
        ;
    }*/

    // /**
    //  * @return Fichefrais[] Returns an array of Fichefrais objects
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
    public function findOneBySomeField($value): ?Fichefrais
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
