<?php

namespace App\Controller;

use App\Repository\DemandesRepository;
use App\Repository\LigneCommandeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LigneCommandeController extends AbstractController
{
    /**
     * @Route("admin/ligne/commande", name="ligne_commande")
     */
    public function index(LigneCommandeRepository $rep ,DemandesRepository $repp): Response
    {
        $demandes=$repp->findAll();
        $ligne = $rep->findAll();
        return $this->render('dashboard/ligne_commande/index.html.twig', [
            'tab' => $ligne,
            'demandes'=>$demandes,
        ]);
    }

    /**
     * @Route("/admin/suppligne/{id}", name="adminlignesupp")
     */

    function supprimercommande($id ,LigneCommandeRepository $rep)
    {
         $ligne = $rep->find($id);
         $em=$this->getDoctrine()->getManager(); 
         $em->remove($ligne);
         $em->flush(); 

     return $this->redirectToRoute('ligne_commande');

    }


}
