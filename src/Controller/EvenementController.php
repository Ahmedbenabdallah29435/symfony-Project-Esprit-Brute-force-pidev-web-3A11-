<?php

namespace App\Controller;

use DateTime;
use DateTimeImmutable;
use App\Entity\Evenement;
use App\Entity\Commentaires;
use App\Form\CommentairesType;
use App\Form\EvenementFormType;
use App\Repository\EvenementRepository;
use App\Repository\CommentairesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;





class EvenementController extends AbstractController
{
    /**
     * @Route("/evenement1", name="evenement1")
     */
    public function index(): Response
    {
        return $this->render('front/Evenementsfront.html.twig', [
            'controller_name' => 'EvenementController',
        ]);
    }
  /**
     * @Route("/evenement/aff", name="ev_aff")
     */

    function afficher(EvenementRepository $rep)
    {

         $evenement = $rep->findall();
         return $this->render('evenement/evenementAFFICHAGE.html.twig', [
             'tab' => $evenement
         ]);

    }

    /**
     * @Route("/evenement/add",name="event_add")
     */

    public function Add(Request $request)
     {
        $evenement=new evenement();
        $form=$this->createform(EvenementFormType::class,$evenement);
        $form->add('ajouter',SubmitType::class);


        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
        
          $file = $form->get('affiche')->getData();
             $filename = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('uploads_directory'),$filename);
            $evenement->setaffiche($filename); 
        
            $em=$this->getDoctrine()->getManager();
        $em->persist($evenement);
        $em->flush();
            return $this->redirectToRoute('ev_aff');

         }
        return $this->render("evenement/evenementAJOUT.html.twig", [
            'form_evenement'=>$form->createView(),
        ]);
     }

     
     

     /**
     * @Route("/evenement/delete/{id}", name="ev_delete")
     */

    public function Supprimer($id,EvenementRepository $rep){
        $evenement=$rep->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($evenement);
        $em->flush();

        return $this->redirectToRoute('ev_aff');
    }

     /**
     * @Route("/evenement/update/{id}",name="ev_update")
     */
    public function Update($id,EvenementRepository $rep,Request $request){
        
        $evenement=$rep->find($id);

        $form=$this->createform(EvenementFormType::class,$evenement);
        $form->add('Modifier',SubmitType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
        $file = $form->get('affiche')->getData();
        $filename = md5(uniqid()).'.'.$file->guessExtension();
        $file->move($this->getParameter('uploads_directory'),$filename);
        $evenement->setaffiche($filename); 
        $em=$this->getDoctrine()->getManager();
        $em->persist($evenement);
        $em->flush();
            return $this->redirectToRoute('ev_aff');

        }return $this->render("evenement/evenementUPDATE.html.twig", [
            'form_evenement'=>$form->createView(),
        ]);

     }

     
     /**
     * @Route("/detail/{id}", name="ev_front_detail")
     */
    function detail($id,EvenementRepository $rep,Request $request,CommentairesRepository $repp)
    {   
        
        $evenement = $rep->find($id);
        $comments = $repp->findByEeven($id);
            
       
        $Commentaires = new Commentaires;
        $CommentairesForm=$this ->createForm(CommentairesType::class, $Commentaires);
        $CommentairesForm->handleRequest($request);
        if($CommentairesForm->isSubmitted() && $CommentairesForm->isValid()){
            $Commentaires->setCreatedAt(new DateTimeImmutable());
            $Commentaires->setAnnonces($evenement);
            $parent = $CommentairesForm->get("parent_id")->getData();
              // On va chercher le commentaire correspondant
              $em = $this->getDoctrine()->getManager();

              if($parent != null){
                  $parent = $em->getRepository(Commentaires::class)->find($parent);
              }
  
              // On définit le parent
              $Commentaires->setParent($parent ?? null);
  
              $em->persist($Commentaires);
              $em->flush();
  
              $this->addFlash('message', 'Votre Commentaire a bien été envoyé');
              return $this->redirectToRoute('ev_front_detail', ['id' => $evenement->getId()]);
          }
         
          return $this->render('front/evenementsfrontdetail.html.twig', [
                'tab' => $evenement,
              'Commentaires_Form' => $CommentairesForm->createView(),
              'comments'=>$comments
          ]);
    
    }
     
   
}

