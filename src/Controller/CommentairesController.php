<?php

namespace App\Controller;

use App\Entity\CommentLike;
use App\Entity\Commentaires;
use App\Repository\DemandesRepository;
use App\Repository\EvenementRepository;
use Doctrine\Persistence\ObjectManager;
use App\Repository\CommentLikeRepository;
use App\Repository\CommentairesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
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
#***************************MOBILE*******************************#
    
    /**
     * @Route("/Commentairesjson", name="json_commentaires", methods={"GET"})
     */
    public function afficherJSON(CommentairesRepository $CommentairesRepository, SerializerInterface $serializer,Request $request): Response
    {
        $id_event= $request->get('id');


        $result = $CommentairesRepository->findByEeven($id_event);

        $json = $serializer->serialize($result, 'json', ['groups' => 'commentaires:read']);
        return new JsonResponse($json, 200, [], true);
    }
   

    /**********************************************************/

     /**
     * @Route("/commentairejson", name="addCommenhbjktairesJSffON")
     * @Method("POST")
     */
    public function addcomentaires(Request $request,CommentairesRepository $CommentairesRepository,EvenementRepository $eve, SerializerInterface $serializer)
    {  
      
        $em = $this->getDoctrine()->getManager();
        $Commentaire = new Commentaires();

        $id = $request->get('id');
        $email = $request->get('Email');
        $pseudo = $request->get('pseudo');
        $contenu = $request->get('contenu');


        $id_eve = $eve->find($id);

        $Commentaire->setAnnonces($id_eve);
        $Commentaire->setEmail($email);
        $Commentaire->setPseudo($pseudo);
        $Commentaire->setContenu($contenu);
        

        $em->persist($Commentaire);
        $em->flush();
        
        $json = $serializer->serialize($Commentaire, 'json', ['groups' => ['normal']]);
        return new JsonResponse($json, 200, [], true);

  
    }

    /**
     * @Route("/deleteCommentairess", name="deleteCommantaire")
     * @Method("DELETE")
     */
    public function supprimerjson(Request $request)
    {
        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();

        $commentaire = $em->getRepository(Commentaires::class)->find($id);
        if ($commentaire!=null)
        {
            $em->remove($commentaire);
            $em->flush();
            $serializer = new Serializer([new ObjectNormalizer()]);
            $formatted = $serializer->normalize("Commentaire supprimee ");
            return new JsonResponse($formatted);

        }

        return new JsonResponse("id commentaire invalid");


    }
    
 


}