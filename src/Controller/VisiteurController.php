<?php

namespace App\Controller;

use PDOException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

            //recuperation des dates//
            $month=date('m');
            $year=date('Y');
            $yearmonth=date('Y-m');
            $monthstart="-01";
            $yearmonthday=date('Y-m').$monthstart;

            //recuperation de l'id de la session pour select les fiches//
            $idVisiteur=$session->get('id');
            $entityManager=$this->getDoctrine()->getManager();

            dump($yearmonth);
            dump($month);
            dump($yearmonthday);

            //recuperation de fiches de l'annee courante pour chaque mois//
            $fichesfraisanneecourante=[];
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
                        $unmontanttotalfraisforfait=0;
                        foreach($lignesfraisforfait as $unelignefraisforfait){
                            $idlignefraisforfait=$unelignefraisforfait->getIdfraisforfait()->getIdfraisforfait();
                            $fraisforfait[$idlignefraisforfait]=$unelignefraisforfait;
                            //calcule du montant total des frais forfait de (objet)unefichefrais//
                            $unmontanttotalfraisforfait=$unmontanttotalfraisforfait +
                                ($unelignefraisforfait->getQuantite() *
                                $unelignefraisforfait->getIdfraisforfait()->getMontantfraisforfait());
                        }
                        //modification de la (key) du montanttotalfraisforfait//
                        $montanttotalfraisforfait['montanttotalfraisforfait']=$unmontanttotalfraisforfait;
                        //ajout du montant total frais forfait à (objet)unefichefrais//
                        $unefichefrais=$unefichefrais->convertObjectClass(
                            array_merge((array)$unefichefrais, (array)$fraisforfait
                            ),'App\Entity\Fichefrais');
                        //ajout des frais forfait à (objet)unefichefrais//
                        $unefichefrais=$unefichefrais->convertObjectClass(
                            array_merge((array)$unefichefrais, (array)$montanttotalfraisforfait
                            ),'App\Entity\Fichefrais');
                    }

                    //recuperation de touts les frais hors forfait de la fiche par l'id//
                    $repositoryfraishorsforfait=$entityManager->getRepository(Entity\Lignefraishorsforfait::class);
                    $lignesfraishorsforfait=$repositoryfraishorsforfait->findBy(['idfichefrais'=>$idFichefrais]);

                    //verification de l'existance de frais hors forfait//
                    if($lignesfraishorsforfait){
                        //modification de la (key) du fraishorsforfait par son id//
                        $fraishorsforfait=[];
                        $unmontanttotalfraishorsforfait=0;
                        //$Listelignesfraishorsforfait=new Entity\Listelignesfraishorsforfait();//
                        foreach ($lignesfraishorsforfait as $unelignefraishorsforfait) {
                            $idlignefraishorsforfait=$unelignefraishorsforfait->getIdlignefraishorsforfait();
                            $fraishorsforfait[$idlignefraishorsforfait]=$unelignefraishorsforfait;
                            //calcule du montant total des frais hors forfait de (objet)unefichefrais//
                            $unmontanttotalfraishorsforfait=$unmontanttotalfraishorsforfait +
                                $unelignefraishorsforfait->getMontant();
                            //ajout des lignes de frais hors forfait à (List)lignesfraishorsforfait//
                            //$Listelignesfraishorsforfait->addLignefraishorsforfait($unelignefraishorsforfait);
                        }
                        //ajout de frais hors forfait à (array)Listelignesfraishorsforfait identifié par la key['lignesfraishorsforfait']//
                        $Listelignesfraishorsforfait['lignesfraishorsforfait']=$fraishorsforfait;
                        //modification de la (key) du montanttotalfraisforfait//
                        $montanttotalfraishorsforfait['montanttotalfraishorsforfait']=$unmontanttotalfraishorsforfait;

                        //ajout de la liste des frais hors forfait à (objet)Listelignesfraishorsforfait identifié par la key['lignesfraishorsforfait']//
                        /*$fraishorsforfait['lignesfraishorsforfait'] = unserialize(sprintf('O:%d:"%s"%s',
                            strlen('App\Entity\Listelignesfraishorsforfait'), 'App\Entity\Listelignesfraishorsforfait',
                            strstr(serialize(
                            array_merge((array)$fraishorsforfait, (array)$Listelignesfraishorsforfait
                        )), ':')));*/

                        //ajout des frais hors forfait à (objet)unefichefrais//
                        $unefichefrais=$unefichefrais->convertObjectClass(
                            array_merge((array)$unefichefrais, (array)$Listelignesfraishorsforfait
                            ),'App\Entity\Fichefrais');
                        //ajout du montant total frais hors forfait à (objet)unefichefrais//
                        $unefichefrais=$unefichefrais->convertObjectClass(
                            array_merge((array)$unefichefrais, (array)$montanttotalfraishorsforfait
                            ),'App\Entity\Fichefrais');

                    }/*
                    else{
                        //création d'un nouveau frais hors forfait vide//
                        $unfraishorsforfaitvide= new Entity\Lignefraishorsforfait();
                        $unfraishorsforfaitvide->setMontant(0);
                        $unmontanttotalfraishorsforfaitvide=0;
                        $fraishorsforfaitvide[1]=$unfraishorsforfaitvide;
                        //ajout de frais hors forfait vide à (array)Listelignesfraishorsforfaitvide identifié par la key['lignesfraishorsforfait']//
                        $Listelignesfraishorsforfaitvide['lignesfraishorsforfait']=$fraishorsforfaitvide;
                        //modification de la (key) du montanttotalfraisforfait//
                        $montanttotalfraishorsforfaitvide['montanttotalfraishorsforfait']=$unmontanttotalfraishorsforfaitvide;

                        //ajout des frais hors forfait vide à (objet)unefichefrais//
                        $unefichefrais=$unefichefrais->convertObjectClass(
                            array_merge((array)$unefichefrais, (array)$Listelignesfraishorsforfaitvide
                            ),'App\Entity\Fichefrais');
                        //ajout du montant total frais hors forfait vide à (objet)unefichefrais//
                        $unefichefrais=$unefichefrais->convertObjectClass(
                            array_merge((array)$unefichefrais, (array)$montanttotalfraishorsforfaitvide
                            ),'App\Entity\Fichefrais');
                    }*/

                    //ajout des fiches à (array)fichesfraiscompletes//
                    array_push($fichesfraisanneecourante, $unefichefrais);
                }
            }
            //recuperation de fiches de l'annee passe pour chaque mois//
            $fichesfraisanneepasse=[];
            for($i=12; $i >=1; $i--){
                $date=new \DateTime();
                $date->setDate($year-1,$i,01);

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
                        $unmontanttotalfraisforfait=0;
                        foreach($lignesfraisforfait as $unelignefraisforfait){
                            $idlignefraisforfait=$unelignefraisforfait->getIdfraisforfait()->getIdfraisforfait();
                            $fraisforfait[$idlignefraisforfait]=$unelignefraisforfait;
                            //calcule du montant total des frais forfait de (objet)unefichefrais//
                            $unmontanttotalfraisforfait=$unmontanttotalfraisforfait +
                                ($unelignefraisforfait->getQuantite() *
                                    $unelignefraisforfait->getIdfraisforfait()->getMontantfraisforfait());
                        }
                        //modification de la (key) du montanttotalfraisforfait//
                        $montanttotalfraisforfait['montanttotalfraisforfait']=$unmontanttotalfraisforfait;
                        //ajout du montant total frais forfait à (objet)unefichefrais//
                        $unefichefrais=$unefichefrais->convertObjectClass(
                            array_merge((array)$unefichefrais, (array)$fraisforfait
                            ),'App\Entity\Fichefrais');
                        //ajout des frais forfait à (objet)unefichefrais//
                        $unefichefrais=$unefichefrais->convertObjectClass(
                            array_merge((array)$unefichefrais, (array)$montanttotalfraisforfait
                            ),'App\Entity\Fichefrais');
                    }

                    //recuperation de touts les frais hors forfait de la fiche par l'id//
                    $repositoryfraishorsforfait=$entityManager->getRepository(Entity\Lignefraishorsforfait::class);
                    $lignesfraishorsforfait=$repositoryfraishorsforfait->findBy(['idfichefrais'=>$idFichefrais]);

                    //verification de l'existance de frais hors forfait//
                    if($lignesfraishorsforfait){
                        //modification de la (key) du fraishorsforfait par son id//
                        $fraishorsforfait=[];
                        $unmontanttotalfraishorsforfait=0;
                        //$Listelignesfraishorsforfait=new Entity\Listelignesfraishorsforfait();//
                        foreach ($lignesfraishorsforfait as $unelignefraishorsforfait) {
                            $idlignefraishorsforfait=$unelignefraishorsforfait->getIdlignefraishorsforfait();
                            $fraishorsforfait[$idlignefraishorsforfait]=$unelignefraishorsforfait;
                            //calcule du montant total des frais hors forfait de (objet)unefichefrais//
                            $unmontanttotalfraishorsforfait=$unmontanttotalfraishorsforfait +
                                $unelignefraishorsforfait->getMontant();
                            //ajout des lignes de frais hors forfait à (List)lignesfraishorsforfait//
                            //$Listelignesfraishorsforfait->addLignefraishorsforfait($unelignefraishorsforfait);
                        }
                        //ajout de frais hors forfait à (array)Listelignesfraishorsforfait identifié par la key['lignesfraishorsforfait']//
                        $Listelignesfraishorsforfait['lignesfraishorsforfait']=$fraishorsforfait;
                        //modification de la (key) du montanttotalfraisforfait//
                        $montanttotalfraishorsforfait['montanttotalfraishorsforfait']=$unmontanttotalfraishorsforfait;

                        //ajout de la liste des frais hors forfait à (objet)Listelignesfraishorsforfait identifié par la key['lignesfraishorsforfait']//
                        /*$fraishorsforfait['lignesfraishorsforfait'] = unserialize(sprintf('O:%d:"%s"%s',
                            strlen('App\Entity\Listelignesfraishorsforfait'), 'App\Entity\Listelignesfraishorsforfait',
                            strstr(serialize(
                            array_merge((array)$fraishorsforfait, (array)$Listelignesfraishorsforfait
                        )), ':')));*/

                        //ajout des frais hors forfait à (objet)unefichefrais//
                        $unefichefrais=$unefichefrais->convertObjectClass(
                            array_merge((array)$unefichefrais, (array)$Listelignesfraishorsforfait
                            ),'App\Entity\Fichefrais');
                        //ajout du montant total frais hors forfait à (objet)unefichefrais//
                        $unefichefrais=$unefichefrais->convertObjectClass(
                            array_merge((array)$unefichefrais, (array)$montanttotalfraishorsforfait
                            ),'App\Entity\Fichefrais');

                    }/*
                    else{
                        //création d'un nouveau frais hors forfait vide//
                        $unfraishorsforfaitvide= new Entity\Lignefraishorsforfait();
                        $unfraishorsforfaitvide->setMontant(0);
                        $unmontanttotalfraishorsforfaitvide=0;
                        $fraishorsforfaitvide[1]=$unfraishorsforfaitvide;
                        //ajout de frais hors forfait vide à (array)Listelignesfraishorsforfaitvide identifié par la key['lignesfraishorsforfait']//
                        $Listelignesfraishorsforfaitvide['lignesfraishorsforfait']=$fraishorsforfaitvide;
                        //modification de la (key) du montanttotalfraisforfait//
                        $montanttotalfraishorsforfaitvide['montanttotalfraishorsforfait']=$unmontanttotalfraishorsforfaitvide;

                        //ajout des frais hors forfait vide à (objet)unefichefrais//
                        $unefichefrais=$unefichefrais->convertObjectClass(
                            array_merge((array)$unefichefrais, (array)$Listelignesfraishorsforfaitvide
                            ),'App\Entity\Fichefrais');
                        //ajout du montant total frais hors forfait vide à (objet)unefichefrais//
                        $unefichefrais=$unefichefrais->convertObjectClass(
                            array_merge((array)$unefichefrais, (array)$montanttotalfraishorsforfaitvide
                            ),'App\Entity\Fichefrais');
                    }*/

                    //ajout des fiches à (array)fichesfraiscompletes//
                    array_push($fichesfraisanneepasse, $unefichefrais);
                }
            }
            dump($fichesfraisanneecourante);
            dump($fichesfraisanneepasse);
            dump($session);

            return $this->render('visiteur/espace.html.twig', [
                'session' => $session,
                'fichesfraisanneecourante' => $fichesfraisanneecourante,
                'fichesfraisanneepasse' => $fichesfraisanneepasse,
                'yearmonth' => $yearmonth,
                'year' => $year,
                'month' => $month,
                'ajouterfichefrais' => $ajouterfichefrais
            ]);
        }
        catch(PDOException $e){

        }

    }

    public function setfraisforfait(Request $request): Response{
        try{
            $qtETP=$request->request->get('qtETP');
            $qtKM=$request->request->get('qtKM');
            $qtNUI=$request->request->get('qtNUI');
            $qtREP=$request->request->get('qtREP');
            $idfichefrais=$request->request->get('idfichefrais');

            $fraisforfaits=[];
            $fraisforfait=[$qtETP,$qtKM,$qtNUI,$qtREP];
            array_push($fraisforfaits,$fraisforfait);
            dump($fraisforfait);

            $entityManager=$this->getDoctrine()->getManager();

            $repositoryLignefraisforfait=$entityManager->getRepository(Entity\Lignefraisforfait::class);
            $lignesfraisforfait=$repositoryLignefraisforfait->findBy(['idfichefrais'=>$idfichefrais]);
            dump($lignesfraisforfait);

            $i=0;
            foreach($lignesfraisforfait as $unelignefraisforfait){
                $unelignefraisforfait->setQuantite($fraisforfait[$i]);
                $i+=1;
                $entityManager->persist($unelignefraisforfait);
                $entityManager->flush();
            }
            dump($lignesfraisforfait);

            //recuperation de la fichefrais
            $repositoryFichefrais =$entityManager->getRepository(Entity\Fichefrais::class);
            $fichefrais=$repositoryFichefrais->findOneBy(['idfichefrais'=>$idfichefrais]);

            //modification des frais de la fichefrais
            /*($lignefraisforfaitETP = new Lignefraisforfait())
                ->setIdfichefrais($fichefrais)
                ->setQuantite($qtETP);
            ($lignefraisforfaitKM = new Lignefraisforfait())
                ->setIdfichefrais($fichefrais)
                ->setQuantite($qtKM);
            ($lignefraisforfaitNUI = new Lignefraisforfait())
                ->setIdfichefrais($fichefrais)
                ->setQuantite($qtNUI);
            ($lignefraisforfaitREP = new Lignefraisforfait())
                ->setIdfichefrais($fichefrais)
                ->setQuantite($qtREP);

            $fichefrais->setDatemodif(($date = new \DateTime())->setDate(date('Y-m-d')));

            $entityManager
                ->persist($lignefraisforfaitETP)
                ->persist($lignefraisforfaitKM)
                ->persist($lignefraisforfaitNUI)
                ->persist($lignefraisforfaitREP)
                ->persist($fichefrais);

            /*return $this->redirectToRoute('visiteur', [
                'session' => $session
            ]);*/
        }
        catch (PDOException $e){
        }
    }
}
