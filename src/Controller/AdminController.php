<?php

namespace App\Controller;

use App\Entity\Utilisateurs;
use App\Form\UtilisateursType;
use App\Repository\ProduitRepository;
use App\Repository\CommandeRepository;
use App\Repository\DemandesRepository;
use App\Repository\VehiculeRepository;
use App\Repository\LivraisonRepository;
use App\Repository\ReclamationRepository;
use App\Repository\UtilisateursRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AdminController extends AbstractController
{
   
    /**
     * @Route("/admin/dashboard", name="admin_dashboard")
     */
    public function index(CommandeRepository $comRepo,ReclamationRepository $rep , UtilisateursRepository $urp, DemandesRepository $repD,ProduitRepository $repp, VehiculeRepository $reppo)
    {
        //$nbr = $urp->CountClient();

        $demandes=$repD->findAll();
        $reclamations = $rep->countByType();

        $produits = $repp->countByQuantite();

        $vehicules = $reppo->countByType();

        $utilisateurs=$urp->countByAge();

      $commandes = $comRepo->countByDate();

      $dates = [];
      $commandesCount = [];


      
      
        $recType = [];
        $recCount = []; 

        $prodQuantite = [];
        $prodNom = [];
        $prodCount = [];

        $typeVehicule = [];
        $vehCount = [];

         $ageUtilisateurs = [];
         $countUtilisateurs = [];

      

     
        foreach($reclamations as $reclamation){
        
        //$recType[] = $reclamation->getType();
        $recType[] = $reclamation ['type'];
        $recCount[]= $reclamation ['count'];
        //$recCount[] = count($recType);
        }

        foreach($produits as $prod){
        
            //$recType[] = $produit->getType();
            $prodQuantite[] = $prod ['quantite'];
            $prodNom[]= $prod ['nom'];
            //$recCount[] = count($recType);
            }
        
            foreach($vehicules as $veh){
        
                //$recType[] = $produit->getType();
                $typeVehicule[] = $veh ['typeV'];
                $vehCount[]= $veh ['countV'];
                //$recCount[] = count($recType);
                }

        foreach ($utilisateurs as $users) {

             //$recType[] = $produit->getType();
             $ageUtilisateurs[] = $users['age'];
             $countUtilisateurs[] = $users['count'];
             //$recCount[] = count($recType);
         }
        //     //$recType[] = $produit->getType();
        //     $ageUtilisateurs[] = $users['age'];
        //     $countUtilisateurs[] = $users['count'];
        //     //$recCount[] = count($recType);
        // }
        
        // On va chercher le nombre de commande par date
        foreach($commandes as $Commande){
            $dates[] = $Commande ['dateCommande'];
            $commandesCount[] = $Commande['count'];
        }

       
            

           
       
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'AdminController',
            'recType' => json_encode($recType),

            'recCount' => json_encode($recCount),
            'demandes' => $demandes,

            'prodQuantite' => json_encode($prodQuantite),
            'prodNom' => json_encode($prodNom),
            
            'typeVehicule' => json_encode($typeVehicule),
            'vehCount' => json_encode($vehCount),

             'ageUtilisateurs' => json_encode($ageUtilisateurs),
             'countUtilisateurs' => json_encode($countUtilisateurs),

            'dates' => json_encode($dates),
            'commandesCount' => json_encode($commandesCount),

      
           

        ]);
    }
    /**
     * @return Reponse
     * @Route("/admin/dashboard/listU", name="admin_userlist")
     */
    public function afficherUser(UtilisateursRepository $rep, DemandesRepository $repp){
        $users=$rep->findAll();
        $demandes=$repp->findAll();
        return $this->render('/dashboard/userslist.html.twig', [
            'users' => $users,
            'demandes' => $demandes,
        ]);
    }
     /**
     * @return Reponse
     * @Route("/dashboard/listD", name="admin_demandelist")
     */
    public function afficherDemande(DemandesRepository $rep){
        $demandes=$rep->findAll();
        return $this->render('/dashboard/demandelist.html.twig', [
            'demandes' => $demandes,
        ]);
    }
     /**
     * @return Reponse
     * @Route("/admin/dashboard/listU/delete/{id}", name="admin_userdelete")
     */

    public function DeleteUser($id,UtilisateursRepository $rep){
        $user=$rep->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('admin_userlist');
    }

     /**
     * @return Reponse
     * @Route("/admin/dashboard/listD/delete/{id}", name="admin_demdelete")
     */

    public function DeleteDem($id,DemandesRepository $rep){
        $dem=$rep->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($dem);
        $em->flush();

        return $this->redirectToRoute('admin_demandelist');
    }

    /**
     * @return Reponse
     * @Route("/admin/dashboard/listD/accept/{id}", name="admin_demaccept")
     */

    public function AcceptDem($id,DemandesRepository $rep, UserPasswordEncoderInterface $encoder){
        $dem=$rep->find($id);
        $user= new Utilisateurs();
        $user->setNom($dem->getNom());
        $user->setPrenom($dem->getPrenom());
        $user->setEmail($dem->getEmail());
        $user->setTelephone($dem->getTelephone());
        $user->setRole($dem->getRole());

        $hash = $encoder->encodePassword($user, $dem->getPassword());
        $user->setPassword($hash);

        $user->setQuestionSecurite1($dem->getQuestionSecurite1());
        $user->setQuestionSecurite2($dem->getQuestionSecurite2());

        $user->setEtat($dem->getEtat());


        $em=$this->getDoctrine()->getManager();
        $em->remove($dem);
        $em->Persist($user);
        $em->flush();

        return $this->redirectToRoute('admin_demandelist');
    }

     /**
     * @Route("admin/loginadmin", name="admin_loginadmin")
     */
    public function login(): Response
    {
        return $this->render('dashboard/loginadmin.html.twig');
    }
     /**
     * @Route("admin/logout", name="admin_logoutadmin")
     */
    public function logout(){}
}
