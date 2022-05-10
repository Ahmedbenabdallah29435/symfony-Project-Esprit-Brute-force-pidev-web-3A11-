<?php

namespace App\Controller;

use App\Entity\Reponse;
use App\Form\ReponseType;
use App\Entity\Reclamation;
use App\Repository\ReponseRepository;
use App\Repository\ReclamationRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReponseController extends AbstractController
{
    /**
     * @Route("/reponse", name="reponse")
     */
    public function index(): Response
    {
        return $this->render('reponse/index.html.twig', [
            'controller_name' => 'ReponseController',
        ]);
    }

    /**
     * @Route("/AfficheReclamationjson", name="json_iffndex", methods={"GET"})
     */
    public function jsonindex(Request $request,ReclamationRepository $ReclamationRepository , SerializerInterface $serializer): Response
    {
        $id_client = $request->get('id');
        $result = $ReclamationRepository->findById($id_client);
        /* $n = $normalizer->normalize($result, null, ['groups' => 'pack:read']);
        $json = json_encode($n); */
        $json = $serializer->serialize($result, 'json', ['groups' => 'Reclamation:read']);
        return new JsonResponse($json, 200, [], true);
    }

    /**
     * @Route("/Reponsejson", name="reeeeepAffiche", methods={"GET"})
     */
    public function afficherReponse(Request $rec, ReclamationRepository $ReclamationRepository , SerializerInterface $serializer, ReponseRepository $reponseRepository): Response
    {
        $rep = new Reponse();
        
        $idreclamation = $rec->query->get("id");
        $rep = $reponseRepository
            ->findBy(array('reclamation' => $idreclamation));

        
        $json = $serializer->serialize($rep, 'json', ['groups' => 'Reponse:read']);
        return new JsonResponse($json, 200, [], true);
    }


      /**
     * @param ReclamationRepository $rep
     * @return Response
     * @Route("admin/reponse/recList", name="list_reclamation")
     */
    public function afficher(ReclamationRepository $rep): Response
    {
        $reclamations=$rep->findAll();
        return $this->render('reponse/listReclamationAdmin.html.twig', [
            'tab' => $reclamations,
        ]);
    }

     /**
     * @Route("/response/deleteRec/{id}", name="reclam_delete")
     */

    public function Delete_reclamation($id, ReclamationRepository $rep){
        $response=$rep->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($response);
        $em->flush();

        return $this->redirectToRoute('list_reclamation');
    }



    /**
     * @Route("/reponse/add/{id}", name="rep_add")
     */
    public function addResponse (Reclamation $recl,Reclamation $subj,Request $req, ReclamationRepository $rep, $id,SessionInterface $session)
    {   
       
        $subject = $session->get("subj", $subj->getSubject());


        $idReclamation=$rep->find($id); 
        
        $reponses= new Reponse();
        $reponses->setCreatedAt(new \DateTimeImmutable());
        $recl->setEtat('Résolue');
        $form=$this ->createForm(ReponseType::class,$reponses);
        $form->add('Add', SubmitType::class);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid())
        { 
            $reponses = $reponses->setReclamation($idReclamation);
            $em=$this->getDoctrine()->getManager();
            $em->persist($reponses);
            $em->flush();
            $this->addFlash('success', 'Your Response is added successfully');
            return $this->redirectToRoute('reponse_list');

        }

        return $this->render('reponse/addReponse.html.twig', [
            'formA'=>$form->createView(), 
            'subject' => $subject
            
        ]);
    }

      /**
     * @param ReponseRepository $rep
     * @return Response
     * @Route("admin/reponse/list", name="reponse_list")
     */
    public function afficher_reponses(ReponseRepository $rep): Response
    {
        $reponses=$rep->findAll();
        return $this->render('reponse/listReponses.html.twig', [
            'tab1' => $reponses,
        ]);
    }

     /**
     * @return Reponse
     * @Route("/response/delete/{id}", name="response_delete")
     */

    public function Delete_reponse($id, ReponseRepository $rep){
        $response=$rep->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($response);
        $em->flush();

        return $this->redirectToRoute('reponse_list');
    }


    /**
     * @Route("reponse/update/{id}", name="reponse_update")
     */
    public function update_reponse(Request $request, $id, ReponseRepository $rep)
    {
       
        $reponse=$rep->find($id);

        $form=$this->createForm(ReponseType::class,$reponse);
       
        $form->add('update', SubmitType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'Your Response is added successfully');

            return $this->redirectToRoute('reponse_list');
        }
        return $this->render('reponse/update.html.twig', [
            'formA'=>$form->createView(),
        ]);
    }

}