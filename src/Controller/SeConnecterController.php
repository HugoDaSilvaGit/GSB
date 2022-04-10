<?php

namespace App\Controller;

use PDOException;
use App\Entity;
use App\Repository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class SeConnecterController extends AbstractController
{
    public function login(Request $request): Response{
        $login=$request->request->get('login');
        $mdp=$request->request->get('mdp');
        if(empty($login) && empty($mdp)){
            //envoie du flashbag pour la notification visiteur//
            $this->get('session')->getFlashBag()->add('connexionSTATE','connexionON');
            return $this->render('login.html.twig', [
            ]);
        }

        try{
            $entityManager=$this->getDoctrine()->getManager();
            $repositoryComptable=$entityManager->getRepository(Entity\Comptable::class);
            $repositoryVisiteur=$entityManager->getRepository(Entity\Visiteur::class);
            $comptable=$repositoryComptable->findOneBy(array('login'=>$login));
            $visiteur=$repositoryVisiteur->findOneBy(array('login'=>$login));

            if (!empty($comptable)){
                $mdpCheck=$comptable->getMdp();

                if($mdp==$mdpCheck){
                    $connexionON=true;
                    $session = new Session();
                    $session->clear();
                    $session->start();
                    $session->set('id',$comptable->getIdcomptable());
                    $session->set('nom',$comptable->getNom());
                    $session->set('prenom',$comptable->getPrenom());
                    $session->set('login',$comptable->getLogin());

                    //envoie du flashbag pour la notification visiteur//
                    $this->get('session')->getFlashBag()->add('connexionSTATE','connexionSUCCESS');
                    return $this->redirectToRoute('comptable', [
                    ]);
                }
                else{
                    //envoie du flashbag pour la notification visiteur//
                    $this->get('session')->getFlashBag()->add('connexionSTATE','connexionOFF');
                    return $this->render('login.html.twig', [
                    ]);
                }
            }
            elseif (!empty($visiteur)){
                $mdpCheck=$visiteur->getMdp();

                if($mdp==$mdpCheck){
                    $connexionON=true;
                    $session = new Session();
                    $session->clear();
                    $session->start();
                    $session->set('id',$visiteur->getIdvisiteur());
                    $session->set('nom',$visiteur->getNom());
                    $session->set('prenom',$visiteur->getPrenom());
                    $session->set('login',$visiteur->getLogin());
                    $session->set('cp',$visiteur->getCp());
                    $session->set('ville',$visiteur->getVille());

                    //envoie du flashbag pour la notification visiteur//
                    $this->get('session')->getFlashBag()->add('connexionSTATE','connexionSUCCESS');
                    return $this->redirectToRoute('visiteur', [
                    ]);
                }
                else{
                    //envoie du flashbag pour la notification visiteur//
                    $this->get('session')->getFlashBag()->add('connexionSTATE','connexionOFF');
                    return $this->render('login.html.twig', [
                    ]);
                }
            }
            else{
                //envoie du flashbag pour la notification visiteur//
                $this->get('session')->getFlashBag()->add('connexionSTATE','connexionOFF');
                return $this->render('login.html.twig', [
                ]);
            }

        }
        catch(PDOException $e){
            //envoie du flashbag pour la notification visiteur//
            $this->get('session')->getFlashBag()->add('connexionSTATE','connexionERROR');
            return $this->render('login.html.twig', [
            ]);

        }
    }

    public function signoff(){
        try {
            $session = new Session();
            $session->start();
            $session->clear();
            //envoie du flashbag pour la notification visiteur//
            $this->get('session')->getFlashBag()->add('connexionSTATE','connexionCLOSED');
            return $this->render('login.html.twig', [
            ]);
        }
        catch (SessionException $e){
            //envoie du flashbag pour la notification visiteur//
            $this->get('session')->getFlashBag()->add('connexionSTATE','connexionERROR');
            return $this->render('login.html.twig', [
            ]);
        }
    }
}
