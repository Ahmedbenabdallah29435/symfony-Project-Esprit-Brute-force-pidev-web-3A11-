<?php

namespace App\Controller;
use App\Entity\Images;
use App\Entity\Boutique;
use App\Form\BoutiqueType;
use App\Entity\Utilisateurs;
use Mediumart\Orange\SMS\SMS;
use App\Repository\ProduitRepository;
use App\Repository\BoutiqueRepository;
use App\Repository\DemandesRepository;
use Mediumart\Orange\SMS\Http\SMSClient;
use App\Repository\UtilisateursRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BoutiqueController extends AbstractController
{

    /**
     * @Route("/commercant", name="commercant")
     */
    public function Commercant(DemandesRepository $repD)
    {
        $demandes = $repD->findAll();
        return $this->render('boutique/commercant.html.twig', [
            'demandes' => $demandes,
        ]);
    }

     /**
     * @param BoutiqueRepository $rep
     * @return Reponse
     * @Route("admin/boutique/list", name="boutique_list")
     */
    public function afficher(BoutiqueRepository $rep,DemandesRepository $repp){
        $boutiques=$rep->findAll();
        $demandes=$repp->findAll();
        return $this->render('boutique/listboutique.html.twig', [
            'tab' => $boutiques,
            'demandes'=>$demandes,
        ]);
    }
    /**
     * @param BoutiqueRepository $rep
     * @return Reponse
     * @Route("/boutique/list/{id}", name="ma_boutique")
     */
    public function maBoutique($id,BoutiqueRepository $rep,UtilisateursRepository $repo ,DemandesRepository $repp)
    {
        $com=$repo->find($id);

        $boutiques = $rep->findByCOM($com);

        $demandes = $repp->findAll();
        return $this->render('boutique/maboutique.html.twig', [
            'tab' => $boutiques,
            'demandes' => $demandes,
        ]);
    }

     

    /**
     * @param ProduitRepository $rep
     * @return Reponse
     * @Route("/boutique/listProduit/{val}", name="boutique_listProduit")
     */
    public function afficherProduit($val,ProduitRepository $rep,DemandesRepository $repp){
        $demandes=$repp->findAll();


        $boutiques=$rep->findByBoutique($val);
        return $this->render('boutique/produitboutique.html.twig', [
            'tab' => $boutiques,
            'demandes'=>$demandes,
        ]);
    }
    /**
     * @Route("/boutique/add",name="boutique_add")
     */

    public function add(Request $request,DemandesRepository $repp,UtilisateursRepository $repo ): Response
    {
        $demandes=$repp->findAll();
        $boutique = new Boutique();
        $form = $this->createForm(BoutiqueType::class,$boutique);
        $form->add('Ajouter',SubmitType::class);
        $users=$repo->findAll();
        
 
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            //on ajoute le commercant
            $user=$this->getUser();
            $user->setBoutique($boutique);
            $boutique->setCommercant($user);

            // On récupère les images transmises
                $images = $form->get('images')->getData();
                
                // On boucle sur les images
                foreach($images as $image){
                    // On génère un nouveau nom de fichier
                    $fichier = md5(uniqid()).'.'.$image->guessExtension();
                    
                    // On copie le fichier dans le dossier uploads
                    $image->move(
                        $this->getParameter('images_directory'),
                        $fichier
                    );
                    
                    // On crée l'image dans la base de données
                    $img = new Images();
                    $img->setName($fichier);
                    $boutique->addImage($img);
                                            }

                     $entityManager = $this->getDoctrine()->getManager();
                     $entityManager->persist($boutique);
                     $entityManager->flush();

                     foreach($users as $user){
                

                        $client = SMSClient::getInstance('xaIAqzMGp0t2Qeh8n40GXr7Q93AkWBBw', '2PhqRiICxtNqSiW7');
                        $sms = new SMS($client);
                        $sms->message('Nous avons ajoute une nouvelle boutique Nom :'.$boutique->getNomBoutique().
                                                                        'Description :'.$boutique->getDescBoutique().
                                                                        'Adresse :'.$boutique->getAdresseBoutique())
                            ->from('+21655841954')
                            ->to("+216".$user->getTelephone())
                            ->send();
                       
            
                        }
                    
                                                          
             
             


             

             return $this->redirectToRoute('produit_add');
        }

        return $this->render('boutique/add.html.twig', [
            'Fbout' => $form->createView(),
            'demandes'=>$demandes,
            
        ]) ;
    }

    /**
     * @Route("/boutique/edit/{id}",name="update_boutique")
     * Method({"GET", "POST"})
     */
    public function update($id, Request $request,DemandesRepository $repp)
    {
        $demandes = $repp->findAll();

        $boutique = new boutique();
        $boutique = $this->getDoctrine()
            ->getRepository(boutique::class)
            ->find($id);


        $form = $this->createformbuilder($boutique)
        ->add('nomBoutique',TextType::class)
        ->add('descBoutique',TextType::class)
        ->add('adresseBoutique',TextType::class)
        ->add('Commercant', EntityType::class, [
           
            'class' => Utilisateurs::class,
            'choice_label' => 'Email',
           
        
            // used to render a select box, check boxes or radios
            // 'multiple' => true,
            // 'expanded' => true,
        ])
        ->add('images', FileType::class,[
            'label' => false,
            'multiple' => true,
            'mapped' => false,
            'required' => false
        ])
        ->add('Edit',SubmitType::class)

        ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
             // On récupère les images transmises
    $images = $form->get('images')->getData();
    
    // On boucle sur les images
    foreach($images as $image){
        // On génère un nouveau nom de fichier
        $fichier = md5(uniqid()).'.'.$image->guessExtension();
        
        // On copie le fichier dans le dossier uploads
        $image->move(
            $this->getParameter('images_directory'),
            $fichier
        );
        
        // On crée l'image dans la base de données
        $img = new Images();
        $img->setName($fichier);
        $boutique->addImage($img);
    }
            
          
            $entityManager = $this->getDoctrine()->getManager();
             $entityManager->flush();
             return $this->redirectToRoute('ma_boutique',['id' => $this->getUser()->getId() ] );
         }
         return $this->render('boutique/update.html.twig', [
             'Fbout' => $form->createView(),
             'bout' => $boutique,
             'demandes'=>$demandes,



            ]);
    }

     /**
     *@Route("/boutique/delete/{id}", name="boutique_delete")
     */

    public function Supprimer($id,BoutiqueRepository $rep){
        $boutique=$rep->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($boutique);
        $em->flush();

        return $this->redirectToRoute('boutique_list');
    }
    

     

}
