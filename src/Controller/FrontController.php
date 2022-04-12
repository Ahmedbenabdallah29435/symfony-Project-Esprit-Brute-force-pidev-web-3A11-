<?php

namespace App\Controller;
use App\Entity\Evenement;
use App\Form\EvenementFormType;
use App\Repository\EvenementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\FileType;


class FrontController extends AbstractController
{
    /**
     * @Route("/front", name="display_front")
     */
    public function index(): Response
    {
        return $this->render('front/index.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }

    /**
     * @Route("/eventfront", name="ev_front_aff")
     */

    function afficher(EvenementRepository $rep)
    {
         $evenement = $rep->findall();
         return $this->render('front/evenementsfront.html.twig', [
             'tab' => $evenement
         ]);

    }
   
}
