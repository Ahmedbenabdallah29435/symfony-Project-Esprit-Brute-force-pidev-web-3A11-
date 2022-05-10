<?php

namespace App\Controller;

use App\Form\MotoType;
use App\Entity\Vehicule;
use App\Form\CamionType;
use App\Form\VoitureType;
use App\Form\VehiculeType;
use App\Repository\DemandesRepository;
use App\Repository\VehiculeRepository;
use App\Repository\UtilisateursRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VehiculeController extends AbstractController
{
    /**
     * @Route("admin/vehicule", name="vehicule")
     */
    public function index(DemandesRepository $repp): Response
    {
        $demandes=$repp->findAll();

        return $this->render('vehicule/index.html.twig', [
            'controller_name' => 'VehiculeController',
            'demandes'=> $demandes,

        ]);
    }
     /**
    * @param VehiculeRepository $rep
    * @return Reponse
    * @Route("admin/voiture/list", name="voi_list")
    */
   public function afficherVoiture(VehiculeRepository $rep,DemandesRepository $repp){
       $demandes=$repp->findAll();
    $voiture=$rep->findByType("voiture");
    return $this->render('vehicule/listVoiture.html.twig', [
        'tab' => $voiture,
        'demandes'=> $demandes,
    ]);
}
  /**
    * @param VehiculeRepository $rep
    * @return Reponse
    * @Route("admin/moto/list", name="mot_list")
    */
    public function afficherMoto(VehiculeRepository $rep,DemandesRepository $repp){
        $demandes=$repp->findAll();
        
        $moto=$rep->findByType("moto");
        return $this->render('vehicule/listMoto.html.twig', [
            'tab' => $moto,
            'demandes'=> $demandes,

        ]);
    }
    /**
    * @param VehiculeRepository $rep
    * @return Reponse
    * @Route("admin/camion/list", name="cam_list")
    */
    public function afficherCamion(VehiculeRepository $rep,DemandesRepository $repp){
        $demandes=$repp->findAll();

        $moto=$rep->findByType("camion");
        return $this->render('vehicule/listCamion.html.twig', [
            'tab' => $moto,
            'demandes'=> $demandes,

        ]);
    }
  /**
  * @param $id
  * @param VehiculeRepository $rep
  * @return Reponse
  * @Route("admin/voiture/delete/{id}", name="voi_delete")
  */

 public function SupprimerVoiture($id,VehiculeRepository $rep){
     $vehicule=$rep->find($id);
     $em=$this->getDoctrine()->getManager();
     $em->remove($vehicule);
     $em->flush();

     return $this->redirectToRoute('voi_list');
 }
 /**
  * @param $id
  * @param VehiculeRepository $rep
  * @return Reponse
  * @Route("admin/moto/delete/{id}", name="mot_delete")
  */

  public function SupprimerMoto($id,VehiculeRepository $rep){
    $vehicule=$rep->find($id);
    $em=$this->getDoctrine()->getManager();
    $em->remove($vehicule);
    $em->flush();

    return $this->redirectToRoute('mot_list');
}
/**
  * @param $id
  * @param VehiculeRepository $rep
  * @return Reponse
  * @Route("admin/camion/delete/{id}", name="cam_delete")
  */

  public function SupprimerCamion($id,VehiculeRepository $rep){
    $vehicule=$rep->find($id);
    $em=$this->getDoctrine()->getManager();
    $em->remove($vehicule);
    $em->flush();

    return $this->redirectToRoute('cam_list');
}

 /**
  * @Route("admin/voiture/add",name="voi_add")
  */

 public function AddVoiture(Request $request, UtilisateursRepository $rep,DemandesRepository $repp){
    $demandes=$repp->findAll();


     $vehicule=new Vehicule();
     $form=$this->createform(VoitureType::class,$vehicule);
     $form->add('Ajouter',SubmitType::class);

     $form->handleRequest($request);

     if($form->isSubmitted() && $form->isValid()){
       
        $vehicule->setEtatVehicule("Disponible");
     $em=$this->getDoctrine()->getManager();
     $em->persist($vehicule);
     $em->flush();
         return $this->redirectToRoute('voi_list');

     }return $this->render("vehicule/addVoiture.html.twig", [
         'FVoiture'=>$form->createView(),
         'demandes'=>$demandes,
     ]);
  }
   /**
  * @Route("admin/voiture/update/{id}",name="voi_update")
  */
 public function Update($id,VehiculeRepository $rep,Request $request,DemandesRepository $repp){
    $demandes=$repp->findAll();

     
     $vehicule=$rep->find($id);

     $form=$this->createform(VoitureType::class,$vehicule);
     $form->add('Modifier',SubmitType::class);

     $form->handleRequest($request);

     if($form->isSubmitted() && $form->isValid()){
     $em=$this->getDoctrine()->getManager();
     $em->flush();
         return $this->redirectToRoute('voi_list');

     }return $this->render("vehicule/updateVoiture.html.twig", [
         'FVoiture'=>$form->createView(),
         'demandes'=>$demandes,

     ]);

  }
  /**
  * @Route("admin/moto/add",name="mot_add")
  */

 public function AddMoto(Request $request, UtilisateursRepository $rep,DemandesRepository $repp){
    $demandes=$repp->findAll();

    $vehicule=new Vehicule();
    $form=$this->createform(MotoType::class,$vehicule);
    $form->add('Ajouter',SubmitType::class);

    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()){
       
        $vehicule->setEtatVehicule("Disponible");
    $em=$this->getDoctrine()->getManager();
    $em->persist($vehicule);
    $em->flush();
        return $this->redirectToRoute('mot_list');

    }return $this->render("vehicule/addMoto.html.twig", [
        'FMoto'=>$form->createView(),
        'demandes'=>$demandes,

    ]);
 }
  /**
 * @Route("admin/moto/update/{id}",name="mot_update")
 */
public function UpdateMoto($id,VehiculeRepository $rep,Request $request,DemandesRepository $repp){
    $demandes=$repp->findAll();

    
    $vehicule=$rep->find($id);

    $form=$this->createform(MotoType::class,$vehicule);
    $form->add('Modifier',SubmitType::class);

    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()){
    $em=$this->getDoctrine()->getManager();
    $em->flush();
        return $this->redirectToRoute('mot_list');

    }return $this->render("vehicule/updateMoto.html.twig", [
        'FMoto'=>$form->createView(),
        'demandes'=>$demandes,

    ]);

 }
 /**
  * @Route("admin/camion/add",name="cam_add")
  */
 public function AddCamion(Request $request, UtilisateursRepository $rep,DemandesRepository $repp){
    $demandes=$repp->findAll();

    $vehicule=new Vehicule();
    $form=$this->createform(CamionType::class,$vehicule);
    $form->add('Ajouter',SubmitType::class);

    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()){
       
        $vehicule->setEtatVehicule("Disponible");
    $em=$this->getDoctrine()->getManager();
    $em->persist($vehicule);
    $em->flush();
        return $this->redirectToRoute('cam_list');

    }return $this->render("vehicule/addCamion.html.twig", [
        'FCamion'=>$form->createView(),
        'demandes'=>$demandes,

    ]);
 }
  /**
 * @Route("admin/camion/update/{id}",name="cam_update")
 */
public function UpdateCamion($id,VehiculeRepository $rep,Request $request,DemandesRepository $repp){
    $demandes=$repp->findAll();

    
    $vehicule=$rep->find($id);

    $form=$this->createform(CamionType::class,$vehicule);
    $form->add('Modifier',SubmitType::class);

    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()){
    $em=$this->getDoctrine()->getManager();
    $em->flush();
        return $this->redirectToRoute('cam_list');

    }return $this->render("vehicule/updateCamion.html.twig", [
        'FCamion'=>$form->createView(),
        'demandes'=>$demandes,

    ]);

 }
}
