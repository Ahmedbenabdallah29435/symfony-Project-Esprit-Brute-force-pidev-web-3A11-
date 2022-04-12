<?php

namespace App\Controller;

use App\Repository\EvenementRepository;
use App\Repository\CommentairesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentairesController extends AbstractController
{
    /**
     * @Route("/commentaires/{id}", name="commentaires")
     */
    public function index($id,CommentairesRepository $repp,EvenementRepository $rep ): Response
    {
        $comments = $repp->findByEeven($id);
       $evenement=$rep->find($id);     
        return $this->render('commentaires/index.html.twig', [
            'controller_name' => 'CommentairesController',
            'comments' =>$comments,
            'evenement'=>$evenement
        ]);

       
    }

    /**
     * @Route("/commentaires/delete/{id}", name="com_delete")
     */

    public function Supprimer($id,CommentairesRepository $rep){
     
        $commentaire=$rep->find($id);
        

        $em=$this->getDoctrine()->getManager();
        $em->remove($commentaire);
        $em->flush();

        return $this->redirectToRoute('commentaires', ['id' => $commentaire->getAnnonces()->getId() ]);

        
    }

    



    
    


}
