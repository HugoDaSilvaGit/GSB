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
            $year=date('Y');
            /*date('Y-m-d');*/
            $idVisiteur=$session->get('id');
            $idVisiteur=strval($idVisiteur);
            $entityManager=$this->getDoctrine()->getManager();

            $fichesfraiscompletes=[];
            for($i=1; $i <=12; $i++){

                $date=new \DateTime();
                $date->setDate($year,$i,01);

                $repositoryFichefrais =$entityManager->getRepository(Entity\Fichefrais::class);
                $unefichefrais=$repositoryFichefrais->findOneBy(['idvisiteur'=>$idVisiteur,'date'=>$date]);

                $repositoryfraisforfait=$entityManager->getRepository(Entity\Fraisforfait::class);
                $fraisforfait=$repositoryfraisforfait->findAll();

                $repositoryfraishorsforfait=$entityManager->getRepository(Entity\Lignefraishorsforfait::class);
                $fraishorsforfait=$repositoryfraishorsforfait->findAll();

                if($unefichefrais) {
                    $idFichefrais=$unefichefrais->getIdfichefrais();

                    $repositoryLignefraisforfait=$entityManager->getRepository(Entity\Lignefraisforfait::class);
                    $lignesfraisforfait=$repositoryLignefraisforfait->findBy(['idfichefrais'=>$idFichefrais]);

                    $fraisforfaittest=[];
                    foreach($lignesfraisforfait as $unelignefraisforfait){
                        $idfraisforfait=$unelignefraisforfait->getIdfraisforfait()->getIdfraisforfait();
                        $fraisforfaittest[$idfraisforfait]=$unelignefraisforfait;
                    }

                    $unefichefrais=$unefichefrais->convertObjectClass(
                        array_merge((array)$unefichefrais, (array)$fraisforfaittest
                        ),'App\Entity\Fichefrais');

                    array_push($fichesfraiscompletes, $unefichefrais);
                }
            }
            /*$fichesfrais=$repositoryFichefrais->findFicheFraisByIdVisiteurAndAnnee($idVisiteur,$annee);
            $fichesfrais = json_decode( json_encode($fichesfrais), false);

            $fichesfraiscompletes=[];
            foreach($fichesfrais as $unefichefrais ){
                $repositoryLignefraisforfait =$entityManager->getRepository(Entity\Lignefraisforfait::class);
                $fraisforfaits=$repositoryLignefraisforfait->findFraisForfaitsByIdFicheFrais($unefichefrais->idfichefrais);
                $fraisforfaits = json_decode( json_encode($fraisforfaits), false);
                $fraisforfaits = (array) $fraisforfaits;
                $unefichefrais = (array) $unefichefrais;
                $unefichefrais['fraisforfait']=$fraisforfaits;
            }
            $fichesfraiscompletes = json_decode( json_encode($fichesfraiscompletes), false);*/
            dump($fichesfraiscompletes);
            $devise =' €';
            return $this->render('visiteur/espace.html.twig', [
                'session' => $session,
                'fichesfrais' => $fichesfraiscompletes,
                'year' => $year
            ]);
        }
        catch(PDOException $e){

        }

    }
}
