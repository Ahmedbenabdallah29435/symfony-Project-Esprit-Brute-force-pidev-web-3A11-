<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Repository\ReclamationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatisticController extends AbstractController
{
    /**
     * @Route("/statistic", name="app_statistic")
     */
    public function index(): Response
    {
        return $this->render('statistic/stats.html.twig', [
            'controller_name' => 'StatisticController',
        ]);
    }


   /**
    * @Route("/stats", name="rec_stat")
    */
    public function statistiques (ReclamationRepository $rep){

        //chercher les types de reclamation
        
        /*$reclamations = $rep->findAll();
        
        $recType = [];
        $recCount = [];

        $Hardware = $rep->findByTypeHard();
        //$Software = $rep->findByTypeSoft(); 
        $Reparation = $rep->findByTypeRep();
        */
        
    }
}
