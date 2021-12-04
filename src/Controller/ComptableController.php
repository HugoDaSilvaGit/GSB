<?php

namespace App\Controller;

use PDOException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ComptableController extends AbstractController
{
    /**
     * @Route("/comptable", name="comptable")
     */
    public function espace(SessionInterface $session): Response{

        try{
            $entityManager=$this->getDoctrine()->getManager();

            return $this->render('comptable/espace.html.twig', [
                'session' => $session
            ]);
        }
        catch(PDOException $e){

        }

    }
}
