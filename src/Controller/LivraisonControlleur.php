<?php

namespace App\Controller;

use App\Entity\Livraison;
use App\Form\LivraisonType;
use App\Repository\CommandeRepository;
use App\Repository\DemandesRepository;
use App\Repository\VehiculeRepository;
use App\Repository\LivraisonRepository;
use App\Repository\UtilisateursRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LivraisonControlleur extends AbstractController
{
    /**
     * @Route("admin/livraison/controlleur", name="livraison_controlleur")
     */
    public function index(): Response
    {
        return $this->render('livraison/index.html.twig', [
            'controller_name' => 'LivraisonControlleur',
        ]);
    }
     /**
    * @param LivraisonRepository $rep
    * @return Reponse
    * @Route("admin/livraison/list", name="livraison_list")
    */
   public function afficher(LivraisonRepository $rep,UtilisateursRepository $repp,DemandesRepository $repo,PaginatorInterface $paginator,Request $request){
        $demandes=$repo->findAll();

       $livraisons=$rep->findAll();
       $users=$repp->findAll();
       $donnees = $paginator->paginate(
        $livraisons,
        $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
        1 // Nombre de résultats par page
       );
       return $this->render('dashboard/livraison/listlivraison.html.twig', [
        'tab' => $donnees,
           'users'=>$users,
           'demandes'=>$demandes,
           
       ]);
   }
   
     /**
     * @param $id
     * @param LivraisonRepository $rep
     * @return Reponse
     * @Route("admin/livraison/delete/{id}", name="livraison_delete")
     */

    public function Supprimer($id,LivraisonRepository $rep,UtilisateursRepository $repp,VehiculeRepository $reppp){
        $livraison=$rep->find($id);
        
        $em=$this->getDoctrine()->getManager();
        $em->remove($livraison);
        $user=$repp->findByEmail($livraison->getLivreur()->getEmail());
        $user->setEtat("Disponible");
        $vehicule=$reppp->find($livraison->getVehicule()->getId());
        $vehicule->setEtatVehicule("Disponible");
        $em->flush();
       

        return $this->redirectToRoute('livraison_list');
    }
    /**
     * @Route("admin/livraison/add/{id}",name="liv_add")
     */

    public function Add($id,Request $request ,UtilisateursRepository $rep,CommandeRepository $repp,VehiculeRepository $reppp,DemandesRepository $repo){
        $demandes=$repo->findAll();

        $livraison=new Livraison();
        $commande=$repp->find($id);

        
        
        $livraison->setEtatLivraison(1);
        $livraison->setPrixLivraison($commande->getMontant());
       

        $form=$this->createform(LivraisonType::class,$livraison);
        $form->add('Ajouter',SubmitType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $vehicule=$reppp->find($livraison->getVehicule()->getId());
            $vehicule->setEtatVehicule("Non Disponible");
        $user=$rep->findByEmail($livraison->getLivreur()->getEmail());
        $user->setEtat("Non Disponible");
        $commande->setEtatCommande(1);
       
     
        
       
        $livraison->setCommande($commande);
       
        
        

        $em=$this->getDoctrine()->getManager();
        $em->persist($livraison);
        $em->flush();
            return $this->redirectToRoute('livraison_list');

        }return $this->render("dashboard/livraison/add.html.twig", [
            'Fclass'=>$form->createView(),
            'demandes'=>$demandes,
        ]);
     }
      /**
     * @Route("admin/livraison/update/{id}",name="livraison_update")
     */
    public function Update($id,LivraisonRepository $rep,Request $request,DemandesRepository $repo){
        $demandes=$repo->findAll();

        $livraison=$rep->find($id);

        $form=$this->createform(LivraisonType::class,$livraison);
        $form->add('Modifier',SubmitType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
        $em=$this->getDoctrine()->getManager();
        $em->flush();
            return $this->redirectToRoute('livraison_list');

        }return $this->render("dashboard/livraison/update.html.twig", [
            'Fclass'=>$form->createView(),
            'demandes'=>$demandes,

        ]);

     }

     
     //******************************JSON*********************************************************************

    /**
    * @Route("/addlivraisonJSON/new",name="addlivraisonJSON")
    */

    public function addlivraisonJSON(Request $request,NormalizerInterface $Normalizer)
    {
	    $em = $this->getDoctrine()->getManager();
        $livraison = new Livraison();
        $livraison->setdateLivraison(new \DateTime('dateLivraison'));
        $livraison->setlivreur($request->get('livreur'));
        $livraison->setvehicule($request->get('vehicule'));
     
        $em->persist($livraison);
        $em->flush();
        $jsonContent = $Normalizer->normalize($livraison, 'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));;
    }

    /**
    * @Route("/listlivraisonJSON", name="listlivraisonJSON")
    */
    public function getlivraisonJSON()
    {   
        $livraison = $this->getDoctrine()
        ->getManager()->getRepository(Livraison::class)->findall();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($livraison);

        return new JsonResponse($formatted);



        //$repository =$this->getDoctrine()->getRepository(livra$livraison::class);
        //$livraisons =$repository-> findAll();

        //$jsonContent = $Normalizer->normalize($livraisons, 'json',['groups'=>'post:read']);
    
            //return $this-> render ('livra$livraison/getAlllivra$livraisonsJSON.html.twig', [
            //'data' => $jsonContent]);
    
        //return new Response(json_encode($jsonContent));;
    }

    /**
    * @Route("/updatelivraisonJSON/{ref}",name="updatelivraisonJSON")
    */

    public function updatelivraisonJSON(Request $request,NormalizerInterface $Normalizer,$ref)
    {
	    $em = $this->getDoctrine()->getManager();
        $livraison = $em->getRepository(Livraison::class)->find($ref);
        $livraison->setdateLivraison(new \DateTime('dateLivraison'));
        $livraison->setlivreur($request->get('livreur'));
        $livraison->setvehicule($request->get('vehicule'));
     
        $em->flush();
        $jsonContent = $Normalizer->normalize($livraison, 'json',['groups'=>'post:read']);
        return new Response("updated successfully".json_encode($jsonContent));;
    }

    /**
    * @Route("/deletelivraisonJSON/{ref}", name="deletelivraisonJSON")
    */
    public function deletelivraisonSON(Request $request,NormalizerInterface $Normalizer,$ref)
    {
        $em = $this->getDoctrine()->getManager();
        $livraison = $em->getRepository(Livraison::class)->find($ref);
        $em->remove($livraison);
        $em->flush();
    
        $jsonContent = $Normalizer->normalize($livraison, 'json',['groups'=>'post:read']);
        return new Response("deleted successfully".json_encode($jsonContent));;
    }









}


