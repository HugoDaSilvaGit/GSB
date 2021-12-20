<?php

namespace App\Controller;

use PDOException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity;
use App\Entity\Fichefrais;
use App\Entity\Lignefraisforfait;
use App\Repository;
use App\Repository\FichefraisRepository;
use Symfony\Component\HttpFoundation\Session\Session;

class VisiteurController extends AbstractController
{
    public function espace(SessionInterface $session): Response{

        try{
            //initialisation de la variables pour ajouter une fiche//
            $ajouterfichefrais=true;

            //recuperation de la date courante//
            $month=date('m');
            $year=date('Y');
            $yearmonth=date('Y-m');

            //recuperation de l'id de la session pour select les fiches//
            $idVisiteur=$session->get('id');
            $entityManager=$this->getDoctrine()->getManager();

            //recuperation de fiches pour chaque mois//
            $fichesfraiscompletes=[];
            for($i=12; $i >=1; $i--){
                $date=new \DateTime();
                $date->setDate($year,$i,01);

                //recuperation de touts les repos pour fill les informations des objets//
                $repositoryFichefrais =$entityManager->getRepository(Entity\Fichefrais::class);
                $unefichefrais=$repositoryFichefrais->findOneBy(['idvisiteur'=>$idVisiteur,'date'=>$date]);

                $repositoryfraisforfait=$entityManager->getRepository(Entity\Fraisforfait::class);
                $fraisforfait=$repositoryfraisforfait->findAll();

                //verification de l'existance de fiches//
                if($unefichefrais) {

                    //verification d'un fiche de frais existante pour le mois courant//
                    if($unefichefrais->getDate()->format('m')==$month) {
                        $ajouterfichefrais=false;
                    }

                    $idFichefrais=$unefichefrais->getIdfichefrais();

                    //recuperation de touts les frais forfait de la fiche par l'id//
                    $repositoryLignefraisforfait=$entityManager->getRepository(Entity\Lignefraisforfait::class);
                    $lignesfraisforfait=$repositoryLignefraisforfait->findBy(['idfichefrais'=>$idFichefrais]);

                    //verification de l'existance de lignes de frais//
                    if($lignesfraisforfait){
                        //modification de la (key) du fraisforfait par son id//
                        $fraisforfait=[];
                        foreach($lignesfraisforfait as $unelignefraisforfait){
                            $idlignefraisforfait=$unelignefraisforfait->getIdfraisforfait()->getIdfraisforfait();
                            $fraisforfait[$idlignefraisforfait]=$unelignefraisforfait;
                        }
                        //ajout des frais forfait à (objet)unefichefrais//
                        $unefichefrais=$unefichefrais->convertObjectClass(
                            array_merge((array)$unefichefrais, (array)$fraisforfait
                            ),'App\Entity\Fichefrais');
                    }

                    //recuperation de touts les frais hors forfait de la fiche par l'id//
                    $repositoryfraishorsforfait=$entityManager->getRepository(Entity\Lignefraishorsforfait::class);
                    $lignesfraishorsforfait=$repositoryfraishorsforfait->findBy(['idfichefrais'=>$idFichefrais]);

                    //verification de l'existance de frais hors forfait//
                    if($lignesfraishorsforfait){
                        $fraishorsforfait=[];
                        $Listelignesfraishorsforfait=new Entity\Listelignesfraishorsforfait();
                        foreach ($lignesfraishorsforfait as $unelignefraishorsforfait) {
                            $idlignefraishorsforfait=$unelignefraishorsforfait->getIdlignefraishorsforfait();

                            //ajout des lignes de frais hors forfait à (List)lignesfraishorsforfait//
                            $Listelignesfraishorsforfait->addLignefraishorsforfait($unelignefraishorsforfait);
                        }

                        //ajout de la liste des frais hors forfait à (objet)Listelignesfraishorsforfait identifié par la key['lignesfraishorsforfait']//
                        $fraishorsforfait['lignesfraishorsforfait'] = unserialize(sprintf('O:%d:"%s"%s',
                            strlen('App\Entity\Listelignesfraishorsforfait'), 'App\Entity\Listelignesfraishorsforfait',
                            strstr(serialize(
                            array_merge((array)$fraishorsforfait, (array)$Listelignesfraishorsforfait
                        )), ':')));

                        $unefichefrais=$unefichefrais->convertObjectClass(
                            array_merge((array)$unefichefrais, (array)$fraishorsforfait
                            ),'App\Entity\Fichefrais');

                    }

                    //ajout des fiches à (array)fichesfraiscompletes//
                    array_push($fichesfraiscompletes, $unefichefrais);
                }
            }
            dump($fichesfraiscompletes);

            return $this->render('visiteur/espace.html.twig', [
                'session' => $session,
                'fichesfrais' => $fichesfraiscompletes,
                'yearmonth' => $yearmonth,
                'year' => $year,
                'ajouterfichefrais' => $ajouterfichefrais
            ]);
        }
        catch(PDOException $e){

        }

    }
}
