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
use App\Entity\Etat;
use App\Entity\Lignefraishorsforfait;
use App\Repository;
use App\Repository\FichefraisRepository;
use Symfony\Component\HttpFoundation\Session\Session;


class VisiteurController extends AbstractController
{
    public function espace(): Response
    {
        try {
            //initialisation de la variables pour ajouter une fiche//
            $ajouterfichefrais = true;

            //recuperation des dates//
            $month = date('m');
            $year = date('Y');
            $yearmonth = date('Y-m');
            $monthstart = "-01";
            $yearmonthday = date('Y-m') . $monthstart;

            //recuperation de l'id de la session//
            $session = $this->get('session');
            $idVisiteur = $session->get('id');

            //recuperation des repository//
            $entityManager = $this->getDoctrine()->getManager();
            $repositoryEtat = $entityManager->getRepository(Entity\Etat::class);
            $repositoryFichefrais = $entityManager->getRepository(Entity\Fichefrais::class);
            $repositoryFraisforfait = $entityManager->getRepository(Entity\Fraisforfait::class);
            $repositoryLignefraisforfait = $entityManager->getRepository(Entity\Lignefraisforfait::class);
            $repositoryLignefraishorsforfait = $entityManager->getRepository(Entity\Lignefraishorsforfait::class);
            $repositoryVisiteur = $entityManager->getRepository(Entity\Visiteur::class);
            $repositoryCvvehicule = $entityManager->getRepository(Entity\Cvvehicule::class);

            //recuperation de fiches de l'annee courante pour chaque mois//
            $fichesfraisanneecourante = [];
            for ($i = 12; $i >= 1; $i--) {
                $date = new \DateTime();
                $date->setDate($year, $i, 01);

                //recuperation de touts les repos pour fill les informations des objets//
                $fraisforfaits = $repositoryFraisforfait->findAll();
                $etats = $repositoryEtat->findAll();
                $cvvehicule = $repositoryCvvehicule->findAll();
                //recuperation de la fichefrais de la boucle en fonction de la date//
                $unefichefrais = $repositoryFichefrais->findOneBy(['idvisiteur' => $idVisiteur, 'date' => $date]);

                //verification de l'existance de fiches//
                if ($unefichefrais) {

                    //verification d'un fiche de frais existante pour le mois courant//
                    if ($unefichefrais->getDate()->format('m') == $month) {
                        $ajouterfichefrais = false;
                    }

                    //recuperation de toutes les lignes frais forfait de la fiche par (objet)Fichefrais//
                    $lignesfraisforfaits = $repositoryLignefraisforfait->findBy(['idfichefrais' => $unefichefrais]);

                    //verification de l'existance de lignes de frais//
                    if ($lignesfraisforfaits) {
                        //modification de la (key) du fraisforfait par son id//
                        $fraisforfait = [];
                        $unmontanttotalfraisforfait = 0;
                        foreach ($lignesfraisforfaits as $unelignefraisforfait) {
                            $idlignefraisforfait = $unelignefraisforfait->getIdfraisforfait()->getIdfraisforfait();
                            $fraisforfait[$idlignefraisforfait] = $unelignefraisforfait;
                            //calcule du montant total des frais forfait de (objet)unefichefrais//
                            $unmontanttotalfraisforfait = $unmontanttotalfraisforfait +
                                ($unelignefraisforfait->getQuantite() *
                                    $unelignefraisforfait->getIdfraisforfait()->getMontantfraisforfait());
                        }
                        //nouveau tarif km//
                        $newkmlignesfraisforfaits = $repositoryLignefraisforfait->findOneBy(['idfichefrais' => $unefichefrais, 'idfraisforfait' => 'KM']);
                        $unmontanttotalfraisforfait = $unmontanttotalfraisforfait + ($newkmlignesfraisforfaits->getQuantite() *
                                $unefichefrais->getIdcv()->getFacteurcv() +
                                $unefichefrais->getIdcv()->getConstantecv());

                        //ancien tarif km//
                        $ancienkmlignesfraisforfaits = $repositoryLignefraisforfait->findOneBy(['idfichefrais' => $unefichefrais, 'idfraisforfait' => 'KM']);
                        $unmontanttotalfraisforfait = $unmontanttotalfraisforfait - ($ancienkmlignesfraisforfaits->getQuantite() *
                                $ancienkmlignesfraisforfaits->getIdfraisforfait()->getMontantfraisforfait());

                        //modification de la (key) du montanttotalfraisforfait//
                        $montanttotalfraisforfait['montanttotalfraisforfait'] = $unmontanttotalfraisforfait;
                        //ajout du montant total frais forfait à (objet)unefichefrais//
                        $unefichefrais = $unefichefrais->convertObjectClass(
                            array_merge((array)$unefichefrais, (array)$fraisforfait
                            ), 'App\Entity\Fichefrais');
                        //ajout des frais forfait à (objet)unefichefrais//
                        $unefichefrais = $unefichefrais->convertObjectClass(
                            array_merge((array)$unefichefrais, (array)$montanttotalfraisforfait
                            ), 'App\Entity\Fichefrais');
                    }

                    //recuperation de touts les frais hors forfait de la fiche par (objet)Fichefrais//
                    $lignesfraishorsforfait = $repositoryLignefraishorsforfait->findBy(['idfichefrais' => $unefichefrais]);

                    //verification de l'existance de frais hors forfait//
                    if ($lignesfraishorsforfait) {
                        //modification de la (key) du fraishorsforfait par son id//
                        $fraishorsforfait = [];
                        $unmontanttotalfraishorsforfait = 0;
                        //$Listelignesfraishorsforfait=new Entity\Listelignesfraishorsforfait();//
                        foreach ($lignesfraishorsforfait as $unelignefraishorsforfait) {
                            $idlignefraishorsforfait = $unelignefraishorsforfait->getIdlignefraishorsforfait();
                            $fraishorsforfait[$idlignefraishorsforfait] = $unelignefraishorsforfait;
                            //calcule du montant total des frais hors forfait de (objet)unefichefrais//
                            $unmontanttotalfraishorsforfait = $unmontanttotalfraishorsforfait +
                                $unelignefraishorsforfait->getMontant();
                        }
                        //ajout de frais hors forfait à (array)Listelignesfraishorsforfait identifié par la key['lignesfraishorsforfait']//
                        $Listelignesfraishorsforfait['lignesfraishorsforfait'] = $fraishorsforfait;
                        //modification de la (key) du montanttotalfraisforfait//
                        $montanttotalfraishorsforfait['montanttotalfraishorsforfait'] = $unmontanttotalfraishorsforfait;

                        //ajout des frais hors forfait à (objet)unefichefrais//
                        $unefichefrais = $unefichefrais->convertObjectClass(
                            array_merge((array)$unefichefrais, (array)$Listelignesfraishorsforfait
                            ), 'App\Entity\Fichefrais');
                        //ajout du montant total frais hors forfait à (objet)unefichefrais//
                        $unefichefrais = $unefichefrais->convertObjectClass(
                            array_merge((array)$unefichefrais, (array)$montanttotalfraishorsforfait
                            ), 'App\Entity\Fichefrais');

                    }
                    //ajout des fiches à (array)fichesfraiscompletes//
                    array_push($fichesfraisanneecourante, $unefichefrais);
                }
            }
            //recuperation de fiches de l'annee passe pour chaque mois//
            $fichesfraisanneepasse = [];
            for ($i = 12; $i >= 1; $i--) {
                $date = new \DateTime();
                $date->setDate($year - 1, $i, 01);

                //recuperation de touts les repos pour fill les informations des objets//
                $fraisforfaits = $repositoryFraisforfait->findAll();
                $etats = $repositoryEtat->findAll();
                $cvvehicule = $repositoryCvvehicule->findAll();
                //recuperation de la fichefrais de la boucle en fonction de la date//
                $unefichefrais = $repositoryFichefrais->findOneBy(['idvisiteur' => $idVisiteur, 'date' => $date]);

                //verification de l'existance de fiches//
                if ($unefichefrais) {

                    //verification d'un fiche de frais existante pour le mois courant//
                    if ($unefichefrais->getDate()->format('m') == $month) {
                        $ajouterfichefrais = false;
                    }

                    //recuperation de toutes les lignes frais forfait de la fiche par (objet)Fichefrais//
                    $lignesfraisforfaits = $repositoryLignefraisforfait->findBy(['idfichefrais' => $unefichefrais]);

                    //verification de l'existance de lignes de frais//
                    if ($lignesfraisforfaits) {
                        //modification de la (key) du fraisforfait par son id//
                        $fraisforfait = [];
                        $unmontanttotalfraisforfait = 0;
                        foreach ($lignesfraisforfaits as $unelignefraisforfait) {
                            $idlignefraisforfait = $unelignefraisforfait->getIdfraisforfait()->getIdfraisforfait();
                            $fraisforfait[$idlignefraisforfait] = $unelignefraisforfait;
                            //calcule du montant total des frais forfait de (objet)unefichefrais//
                            $unmontanttotalfraisforfait = $unmontanttotalfraisforfait +
                                ($unelignefraisforfait->getQuantite() *
                                    $unelignefraisforfait->getIdfraisforfait()->getMontantfraisforfait());
                        }
                        //nouveau tarif km//
                        $newkmlignesfraisforfaits = $repositoryLignefraisforfait->findOneBy(['idfichefrais' => $unefichefrais, 'idfraisforfait' => 'KM']);
                        $unmontanttotalfraisforfait = $unmontanttotalfraisforfait + ($newkmlignesfraisforfaits->getQuantite() *
                                $unefichefrais->getIdcv()->getFacteurcv() +
                                $unefichefrais->getIdcv()->getConstantecv());

                        //ancien tarif km//
                        $ancienkmlignesfraisforfaits = $repositoryLignefraisforfait->findOneBy(['idfichefrais' => $unefichefrais, 'idfraisforfait' => 'KM']);
                        $unmontanttotalfraisforfait = $unmontanttotalfraisforfait - ($ancienkmlignesfraisforfaits->getQuantite() *
                                $ancienkmlignesfraisforfaits->getIdfraisforfait()->getMontantfraisforfait());

                        //modification de la (key) du montanttotalfraisforfait//
                        $montanttotalfraisforfait['montanttotalfraisforfait'] = $unmontanttotalfraisforfait;
                        //ajout du montant total frais forfait à (objet)unefichefrais//
                        $unefichefrais = $unefichefrais->convertObjectClass(
                            array_merge((array)$unefichefrais, (array)$fraisforfait
                            ), 'App\Entity\Fichefrais');
                        //ajout des frais forfait à (objet)unefichefrais//
                        $unefichefrais = $unefichefrais->convertObjectClass(
                            array_merge((array)$unefichefrais, (array)$montanttotalfraisforfait
                            ), 'App\Entity\Fichefrais');
                    }

                    //recuperation de touts les frais hors forfait de la fiche par (objet)Fichefrais//
                    $lignesfraishorsforfait = $repositoryLignefraishorsforfait->findBy(['idfichefrais' => $unefichefrais]);

                    //verification de l'existance de frais hors forfait//
                    if ($lignesfraishorsforfait) {
                        //modification de la (key) du fraishorsforfait par son id//
                        $fraishorsforfait = [];
                        $unmontanttotalfraishorsforfait = 0;
                        //$Listelignesfraishorsforfait=new Entity\Listelignesfraishorsforfait();//
                        foreach ($lignesfraishorsforfait as $unelignefraishorsforfait) {
                            $idlignefraishorsforfait = $unelignefraishorsforfait->getIdlignefraishorsforfait();
                            $fraishorsforfait[$idlignefraishorsforfait] = $unelignefraishorsforfait;
                            //calcule du montant total des frais hors forfait de (objet)unefichefrais//
                            $unmontanttotalfraishorsforfait = $unmontanttotalfraishorsforfait +
                                $unelignefraishorsforfait->getMontant();
                        }
                        //ajout de frais hors forfait à (array)Listelignesfraishorsforfait identifié par la key['lignesfraishorsforfait']//
                        $Listelignesfraishorsforfait['lignesfraishorsforfait'] = $fraishorsforfait;
                        //modification de la (key) du montanttotalfraisforfait//
                        $montanttotalfraishorsforfait['montanttotalfraishorsforfait'] = $unmontanttotalfraishorsforfait;

                        //ajout des frais hors forfait à (objet)unefichefrais//
                        $unefichefrais = $unefichefrais->convertObjectClass(
                            array_merge((array)$unefichefrais, (array)$Listelignesfraishorsforfait
                            ), 'App\Entity\Fichefrais');
                        //ajout du montant total frais hors forfait à (objet)unefichefrais//
                        $unefichefrais = $unefichefrais->convertObjectClass(
                            array_merge((array)$unefichefrais, (array)$montanttotalfraishorsforfait
                            ), 'App\Entity\Fichefrais');

                    }
                    //ajout de chaques fiches de frais à (array)fichesfraisanneepasse//
                    array_push($fichesfraisanneepasse, $unefichefrais);
                }
            }
            return $this->render('visiteur/espace.html.twig', [
                'session' => $session,
                'fichesfraisanneecourante' => $fichesfraisanneecourante,
                'fichesfraisanneepasse' => $fichesfraisanneepasse,
                'yearmonth' => $yearmonth,
                'year' => $year,
                'month' => $month,
                'ajouterfichefrais' => $ajouterfichefrais,
                'cvvehicule' => $cvvehicule
            ]);
        } catch (PDOException $e) {

        }
    }

    public function setfraisforfait(Request $request): Response
    {
        try {
            //recuperation des quantites de frais//
            $qtETP = $request->request->get('qtETP');
            $qtKM = $request->request->get('qtKM');
            $qtNUI = $request->request->get('qtNUI');
            $qtREP = $request->request->get('qtREP');
            $idfichefrais = $request->request->get('idfichefrais');

            //ajouts des quantites de frais a (array)$qtfraisforfaits//
            $qtfraisforfaits = [$qtETP, $qtKM, $qtNUI, $qtREP];

            //recuperation des repository//
            $entityManager = $this->getDoctrine()->getManager();
            $repositoryEtat = $entityManager->getRepository(Entity\Etat::class);
            $repositoryFichefrais = $entityManager->getRepository(Entity\Fichefrais::class);
            $repositoryFraisforfait = $entityManager->getRepository(Entity\Fraisforfait::class);
            $repositoryLignefraisforfait = $entityManager->getRepository(Entity\Lignefraisforfait::class);
            $repositoryLignefraishorsforfait = $entityManager->getRepository(Entity\Lignefraishorsforfait::class);
            $repositoryVisiteur = $entityManager->getRepository(Entity\Visiteur::class);

            //recuperation de la (objet)fichefrais à modfier//
            $unefichefrais = $repositoryFichefrais->findOneBy(['idfichefrais' => $idfichefrais]);
            //recuperation des lignes frais forfait de la fiche frais//
            $lignesfraisforfaits = $repositoryLignefraisforfait->findBy(['idfichefrais' => $idfichefrais]);

            //verification de la fiche de frais en fonction de sa date (impossible de modifier autre fiche que mois courant)//
            if ($unefichefrais && $lignesfraisforfaits && $unefichefrais->getDate()->format('Y-m') == date('Y-m')) {

                //mise à jour de chaque lignesfraisforfait de (objet)unefichefrais//
                $i = 0;
                foreach ($lignesfraisforfaits as $unelignefraisforfait) {
                    $unelignefraisforfait->setQuantite($qtfraisforfaits[$i]);
                    $i += 1;
                    $entityManager->persist($unelignefraisforfait);
                    $entityManager->flush();
                }
                //modification de datemodif de (objet)unefichedefrais//
                $date = (new \DateTime())->setDate(date('Y'), date('m'), date('d'));
                $unefichefrais->setDatemodif($date);
                $entityManager->persist($unefichefrais);
                $entityManager->flush();

                //envoie du flashbag pour la notification visiteur//
                $this->get('session')->getFlashBag()->add('setfraisforfaitSTATE', 'setfraisforfaitSUCCESS');
                return $this->redirectToRoute('visiteur', [
                ],);
            } else {
                //envoie du flashbag pour la notification visiteur//
                $this->get('session')->getFlashBag()->add('setfraisforfaitSTATE', 'setfraisforfaitERROR');
                return $this->redirectToRoute('visiteur', [
                ],);
            }
        } catch (PDOException $e) {
            //envoie du flashbag pour la notification visiteur//
            $this->get('session')->getFlashBag()->add('PDOExceptionSTATE', 'PDOExceptionERROR');
            return $this->redirectToRoute('signoff', [
            ],);
        }
    }

    public function addfichefrais(Request $request): Response
    {
        try {
            //recuperation des quantités de frais forfait//
            $qtETP = $request->request->get('qtETP');
            $qtKM = $request->request->get('qtKM');
            $qtNUI = $request->request->get('qtNUI');
            $qtREP = $request->request->get('qtREP');
            $cv = $request->request->get('cv');

            //recuperation de l'id de la session//
            $session = $this->get('session');
            $idvisiteur = $session->get('id');

            if ($qtETP && $qtKM && $qtNUI && $qtREP && $cv) {
                //ajouts des quantites de frais à (array)$qtfraisforfaits//
                $qtfraisforfaits = [$qtETP, $qtKM, $qtNUI, $qtREP];

                //recuperation des repository//
                $entityManager = $this->getDoctrine()->getManager();
                $repositoryEtat = $entityManager->getRepository(Entity\Etat::class);
                $repositoryFichefrais = $entityManager->getRepository(Entity\Fichefrais::class);
                $repositoryFraisforfait = $entityManager->getRepository(Entity\Fraisforfait::class);
                $repositoryLignefraisforfait = $entityManager->getRepository(Entity\Lignefraisforfait::class);
                $repositoryLignefraishorsforfait = $entityManager->getRepository(Entity\Lignefraishorsforfait::class);
                $repositoryVisiteur = $entityManager->getRepository(Entity\Visiteur::class);
                $repositoryCvvehicule = $entityManager->getRepository(Entity\Cvvehicule::class);

                //recuperation du (objet)visiteur//
                $visiteur = $repositoryVisiteur->findOneBy(['idvisiteur' => $idvisiteur]);
                //recuperation de touts les type de frais forfait//
                $fraisforfaits = $repositoryFraisforfait->findAll();
                //recuperation de (objet)etat par defaut//
                $etatpardefaut = $repositoryEtat->findOneBy(['idetat' => 'CR']);
                //recuperation de (objet)cvvehicule//
                $cvvehicule = $repositoryCvvehicule->findOneBy(['idcv' => $cv]);

                //creation de (objet)date a attribuer a (objet)nouvellefichefrais//
                $date = (new \DateTime())->setDate(date('Y'), date('m'), 01);

                //creation de (objet)nouvellefichefrais//
                $nouvellefichefrais = new Fichefrais();
                //valorisation de (objet)nouvellefichefrais//
                $nouvellefichefrais->setIdvisiteur($visiteur);
                $nouvellefichefrais->setDate($date);
                $nouvellefichefrais->setIdfichefrais('' . $idvisiteur . '-' . date('Y') . date('m') . '');
                $nouvellefichefrais->setIdetat($etatpardefaut);
                $nouvellefichefrais->setIdcv($cvvehicule);
                $entityManager->persist($nouvellefichefrais);
                $entityManager->flush();

                //creation de chaque lignesfraisforfait pour (objet)nouvellefichefrais//
                $i = 0;
                foreach ($fraisforfaits as $unfraisforfait) {
                    $unelignefraisforfait = new Lignefraisforfait();
                    //valorisation de chaque (objet)unelignefraisforfait pour (objet)nouvellefichefrais//
                    $unelignefraisforfait->setIdfichefrais($nouvellefichefrais);
                    $unelignefraisforfait->setIdfraisforfait($unfraisforfait);
                    $unelignefraisforfait->setQuantite($qtfraisforfaits[$i]);
                    $i += 1;
                    $entityManager->persist($unelignefraisforfait);
                    $entityManager->flush();
                }
                //envoie du flashbag pour la notification visiteur//
                $this->get('session')->getFlashBag()->add('addfichefraisSTATE', 'addfichefraisSUCCESS');
                return $this->redirectToRoute('visiteur', [
                ],);
            } else {
                //envoie du flashbag pour la notification visiteur//
                $this->get('session')->getFlashBag()->add('addfichefraisSTATE', 'addfichefraisERROR');
                return $this->redirectToRoute('visiteur', [
                ],);
            }
        } catch (PDOException $e) {
            //envoie du flashbag pour la notification visiteur//
            $this->get('session')->getFlashBag()->add('PDOExceptionSTATE', 'PDOExceptionERROR');
            return $this->redirectToRoute('signoff', [
            ],);
        }
    }

    public function setfraishorsforfait(Request $request): Response
    {
        try {
            //recuperation des informations de frais hors forfait//
            $arraydateFHF = $request->request->get('dateFHF');
            $arraymontantFHF = $request->request->get('montantFHF');
            $arraylibelleFHF = $request->request->get('libelleFHF');
            $idfichefrais = $request->request->get('idfichefrais');

            //recuperation de l'id de la session//
            $session = $this->get('session');
            $idvisiteur = $session->get('id');

            //recuperation des repository//
            $entityManager = $this->getDoctrine()->getManager();
            $repositoryFichefrais = $entityManager->getRepository(Entity\Fichefrais::class);
            $repositoryLignefraishorsforfait = $entityManager->getRepository(Entity\Lignefraishorsforfait::class);
            $repositoryVisiteur = $entityManager->getRepository(Entity\Visiteur::class);

            //recuperation de la (objet)fichefrais//
            $unefichefrais = $repositoryFichefrais->findOneBy(['idfichefrais' => $idfichefrais]);

            //verification de la fiche de frais en fonction de sa date (impossible d'ajouter des frais hors forfait à des fiches frais antérieur)//
            if ($unefichefrais && $unefichefrais->getDate()->format('Y-m') == date('Y-m')) {

                //recuperation des (objet)lignefraishorsforfait de la (objet)fichefrais si elles existent//
                $lignesfraishorsforfait = $repositoryLignefraishorsforfait->findBy(['idfichefrais' => $idfichefrais]);

                //verification de l'existance de frais hors forfait//
                if ($lignesfraishorsforfait) {
                    //supression de chaque (objet)fraishorsforfait de la (objet)fichefrais//
                    foreach ($lignesfraishorsforfait as $unelignefraishorsforfait) {
                        $entityManager->remove($unelignefraishorsforfait);
                        $entityManager->flush();
                    }
                }

                //création de chaque (objet)lignefraishorsforfait//
                $i = 0;
                //verification de l'existance de montants à valoriser//
                if ($arraymontantFHF) {
                    foreach ($arraymontantFHF as $unmontantFHF) {

                        //creation de (objet)date à attribuer à (objet)unenouvellelignefraishorsforfait//
                        $date = \DateTime::createFromFormat('Y-m-d', $arraydateFHF[$i]);

                        //creation de (objet)unenouvellelignefraishorsforfait//
                        $unenouvellelignefraishorsforfait = new Lignefraishorsforfait();
                        //valorisation de (objet)unenouvellelignefraishorsforfait//
                        $unenouvellelignefraishorsforfait->setIdlignefraishorsforfait($i + 1);
                        $unenouvellelignefraishorsforfait->setIdfichefrais($unefichefrais);
                        $unenouvellelignefraishorsforfait->setDate($date);
                        $unenouvellelignefraishorsforfait->setMontant($unmontantFHF);
                        $unenouvellelignefraishorsforfait->setLibelle($arraylibelleFHF[$i]);
                        $entityManager->persist($unenouvellelignefraishorsforfait);
                        $entityManager->flush();

                        $i += 1;
                    }
                }
                //envoie du flashbag pour la notification visiteur//
                $this->get('session')->getFlashBag()->add('setfraishorsforfaitSTATE', 'setfraishorsforfaitSUCCESS');
                return $this->redirectToRoute('visiteur', [
                ],);
            } else {
                //envoie du flashbag pour la notification visiteur//
                $this->get('session')->getFlashBag()->add('setfraishorsforfaitSTATE', 'setfraishorsforfaitERROR');
                return $this->redirectToRoute('visiteur', [
                ],);
            }
        } catch (PDOException $e) {
            //envoie du flashbag pour la notification visiteur//
            $this->get('session')->getFlashBag()->add('PDOExceptionSTATE', 'PDOExceptionERROR');
            return $this->redirectToRoute('signoff', [
            ],);
        }
    }

    public function removefichefrais(Request $request): Response
    {
        try {
            $idfichefrais = $request->request->get('idfichefrais');

            //recuperation des repository//
            $entityManager = $this->getDoctrine()->getManager();
            $repositoryFichefrais = $entityManager->getRepository(Entity\Fichefrais::class);
            $repositoryLignefraisforfait = $entityManager->getRepository(Entity\Lignefraisforfait::class);
            $repositoryLignefraishorsforfait = $entityManager->getRepository(Entity\Lignefraishorsforfait::class);

            //recuperation de la (objet)fichefrais à modfier//
            $unefichefrais = $repositoryFichefrais->findOneBy(['idfichefrais' => $idfichefrais]);
            //recuperation des lignes frais forfait de la fiche frais//
            $lignesfraisforfaits = $repositoryLignefraisforfait->findBy(['idfichefrais' => $idfichefrais]);
            //recuperation des lignes frais hors forfait de la fiche frais//
            $lignesfraishorsforfait = $repositoryLignefraishorsforfait->findBy(['idfichefrais' => $unefichefrais]);

            //verification de la fiche de frais en fonction de sa date (impossible de supprimer autre fiche que mois courant)//
            if ($unefichefrais && $unefichefrais->getDate()->format('Y-m') == date('Y-m')) {

                //supression de chaques  (objet)lignesfraisforfait de la (objet)fichefrais//
                foreach ($lignesfraisforfaits as $unelignefraisforfait) {
                    $entityManager->remove($unelignefraisforfait);
                }

                //verification de l'existance de frais hors forfait de la (objet)fichefrais//
                if ($lignesfraishorsforfait) {
                    //supression de chaques (objet)lignesfraishorsforfait de la (objet)fichefrais//
                    foreach ($lignesfraishorsforfait as $unelignefraishorsforfait) {
                        $entityManager->remove($unelignefraishorsforfait);
                    }
                }

                $entityManager->remove($unefichefrais);
                $entityManager->flush();

                //envoie du flashbag pour la notification visiteur//
                $this->get('session')->getFlashBag()->add('removefichefraisSTATE', 'removefichefraisSUCCESS');
                return $this->redirectToRoute('visiteur', [
                ],);
            } else {
                //envoie du flashbag pour la notification visiteur//
                $this->get('session')->getFlashBag()->add('removefichefraisSTATE', 'removefichefraisERROR');
                return $this->redirectToRoute('visiteur', [
                ],);
            }
        } catch (PDOException $e) {
            //envoie du flashbag pour la notification visiteur//
            $this->get('session')->getFlashBag()->add('PDOExceptionSTATE', 'PDOExceptionERROR');
            return $this->redirectToRoute('signoff', [
            ],);
        }
    }
}
