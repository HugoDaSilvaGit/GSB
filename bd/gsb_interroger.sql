

SELECT DISTINCT ROW_NUMBER() OVER (PARTITION BY fif.date) AS number, fif.idfichefrais AS idfichefrais , fif.date AS date, fif.nbjustificatifs AS nbjustificatifs, fif.montantvalide AS montantvalide, fif.datemodif AS datemodif, fif.idetat AS idetat, fif.idvisiteur AS idvisiteur, montantTotalFraisForfait, montantTotalFraisHorsForfait
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
WHERE fif.idvisiteur = 'a131'
AND YEAR(fif.date) = '2021'
ORDER BY MONTH(fif.date) ASC;

SELECT lff.idFraisForfait, lff.quantite, lff.quantite*frf.montantFraisForfait
FROM LigneFraisForfait AS lff
INNER JOIN FraisForfait AS frf ON lff.idFraisForfait = frf.idFRaisForfait
WHERE ff.idFicheFrais='a131-202008';

->select(array('fif.idfichefrais, identity(fif.idvisiteur) as idvisiteur, fif.date, fif.nbjustificatifs, fif.montantvalide, fif.datemodif, identity(fif.idetat) as idetat, SUM(frf.montantfraisforfait*lff.quantite) as montanttotalfraisforfait, lfhf.libelle as libellefraishorsforfait, SUM(lfhf.montant) as montanttotalfraishorsforfait'))
            ->from('App:Fichefrais', 'fif')
            ->innerJoin(->select(array()))
            ->innerJoin('App:Lignefraisforfait', 'lff', Join::WITH, 'lff.idfichefrais = fif.idfichefrais')
            ->innerJoin('App:Fraisforfait', 'frf', Join::WITH, 'frf.idfraisforfait = lff.idfraisforfait')
            ->innerJoin('App:Lignefraishorsforfait', 'lfhf', Join::WITH, 'lfhf.idfichefrais = fif.idfichefrais')
            ->innerJoin('App:Etat', 'e', Join::WITH, 'e.idetat = fif.idetat')
            ->where('fif.idvisiteur = :idvisiteur')
            ->setParameter('idvisiteur' , $idvisiteur)
            ->andWhere('YEAR(fif.date) = :annee')
            ->setParameter('annee', $annee)
            ->groupBy('fif.idfichefrais')
            ->groupBy('lfhf.idfichefrais')
            ->orderBy('MONTH(fif.date)', 'ASC')
            ->setMaxResults(12)
            ->getQuery()
            ->getResult()


select distinct fif.idFicheFrais, fif.idVisiteur, lff.quantite,frf.montantFraisForfait, lfhf.montant
from FicheFrais as fif, LigneFraisForfait as lff, FraisForfait as frf, LigneFraisHorsForfait as lfhf
where lff.idFicheFrais = fif.idFicheFrais 
and  lff.idFraisForfait = frf.idFraisForfait
and fif.idFicheFrais = lfhf.idFicheFrais
AND fif.idFicheFrais='a131-202110';
