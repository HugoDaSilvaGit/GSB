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
            $annee=date("Y");
            $idVisiteur=$session->get('id');
            $entityManager=$this->getDoctrine()->getManager();
            $repositoryFichefrais =$entityManager->getRepository(Entity\Fichefrais::class);
            $fichesfrais=$repositoryFichefrais->findFicheFraisByIdVisiteurAndAnnee($idVisiteur,$annee);
            $fichesfrais = json_decode( json_encode($fichesfrais), false);
            $fichesfraiscompletes=[];
            foreach($fichesfrais as $unefichefrais ){
                $repositoryLignefraisforfait =$entityManager->getRepository(Entity\Lignefraisforfait::class);
                $fraisforfaits=$repositoryLignefraisforfait->findFraisForfaitsByIdFicheFrais($unefichefrais->idfichefrais);
                $unefichefrais = (array) $unefichefrais;
                $unefichefrais["fraisforfait"]=$fraisforfaits;
                array_push($fichesfraiscompletes,$unefichefrais);
            }
            $fichesfraiscompletes = json_decode( json_encode($fichesfraiscompletes), false);
            dump($fichesfraiscompletes);
            $devise =' €';
            return $this->render('visiteur/espace.html.twig', [
                'session' => $session,
                'fichesfrais' => $fichesfrais,
                'devise' => $devise,
                'annee' => $annee
            ]);
        }
        catch(PDOException $e){

        }

    }
}
