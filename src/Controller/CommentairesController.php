<?php

namespace App\Controller;

use App\Entity\CommentLike;
use App\Entity\Commentaires;
use App\Repository\DemandesRepository;
use App\Repository\EvenementRepository;
use Doctrine\Persistence\ObjectManager;
use App\Repository\CommentLikeRepository;
use App\Repository\CommentairesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentairesController extends AbstractController
{
    /**
     * @Route("/commentaires/{id}", name="commentaires")
     */
    public function index($id,CommentairesRepository $repp,EvenementRepository $rep,DemandesRepository $reppp ): Response
    {
        $demandes =$reppp->findAll();
        $comments = $repp->findByEeven($id);
       $evenement=$rep->find($id);     
        return $this->render('dashboard/commentaires/index.html.twig', [
            'controller_name' => 'CommentairesController',
            'comments' =>$comments,
            'evenement'=>$evenement,
            'demandes'=>$demandes,
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
    /**
     * @Route("/commentaire/{id}/like",name="commentaire_like")
     * 
     */
    public function like(Commentaires $commentaire, CommentLikeRepository $likerepo ) : Response
    {
        $utilisateur= $this->getUser();
        if(!$utilisateur) return $this->json([
            'code'=> 403,
            'message' => "unauthorized"
        ],403);

        if($commentaire ->isLikedByUtilisateur($utilisateur)){
            $like =$likerepo->findOneBy([
                'comment'=>$commentaire,
                'utilisateur'=>$utilisateur
            ]);
            $em=$this->getDoctrine()->getManager();
            $em->remove($like);
            $em->flush();

            return $this->json([
                'code' => 200,
                'message' => 'Like bien supprimé',
                'likes' => $likerepo->count(['comment'=>$commentaire])
            ],200);
            
        }
        $like = new CommentLike();
        $like->setComment($commentaire)
            ->setUtilisateur($utilisateur);
            $em=$this->getDoctrine()->getManager();
            $em->persist($like);
            $em->flush();
           


        return $this->json([
            'code'=> 200 ,
            'message'=>'Like bien ajouté' ,
            'likes'=>$likerepo->count(['comment'=>$commentaire])
        ],200);
    }



    
    


}
