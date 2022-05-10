<?php

namespace App\Controller;


use App\Entity\Reponse;
use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Services\cart\CartService;
use App\Repository\ReponseRepository;
use App\Repository\CommandeRepository;
use App\Repository\ReclamationRepository;
use App\Repository\UtilisateursRepository;
use App\Repository\LigneCommandeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Snipe\BanBuilder\CensorWords;

class ReclamationController extends AbstractController
{
    /**
     * @Route("/reclamation", name="reclamation")
     */
    public function index(CartService $cartService): Response
    {
        $dataPanier = $cartService->getFullCart();  
        $total = $cartService->getTotal();

        return $this->render('reclamation/index.html.twig', [
            'controller_name' => 'ReclamationController',
            'elements' => $dataPanier,
            'total' => $total
        ]);
    }


    /**
     * @Route("/reclamation/add/{id}/{cmd}", name="r_add")
     */
    public function add ($id,$cmd,Request $req, UtilisateursRepository $rep,CartService $cartService, CommandeRepository $repp)
    {
        $dataPanier = $cartService->getFullCart();  
        $total = $cartService->getTotal();

        $em = $this->getDoctrine()->getManager();
        $reclamations= new Reclamation();
        #$reclamations->setCreatedAt(new \DateTimeImmutable());
        $reclamations->setEtat("En cours");
        $form=$this ->createForm(ReclamationType::class,$reclamations);
        $form->add('Ajouter', SubmitType::class);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid())
        {   $messageContent =$form->get('message')->getData();
            $censor = new CensorWords;
    

            $string = $censor->censorString($messageContent);
            $reclamations->setMessage($string['clean']);
            
            $em=$this->getDoctrine()->getManager();
            $user=$rep->find($id);
            $reclamations->setClient($user);
            
            $commande=$repp->find($cmd);

            $reclamations->setCommande($commande);


            $em->persist($reclamations);
            $em->flush();
            $this->addFlash('success','Reclamation Added Successfully !');
            return $this->redirectToRoute('home');

        }

        return $this->render('reclamation/add.html.twig', [
            'formA'=>$form->createView(),
            'elements' => $dataPanier,
            'total' => $total
        ]);
    }




    /**
     * @param ReclamationRepository $rep
     * @return Response
     * @Route("reclamation/list/{value}", name="r_list")
     */
    public function afficher($value, ReclamationRepository $rep,CartService $cartService,LigneCommandeRepository $ligneCommande, Request $request, PaginatorInterface $paginator): Response
    {    $dataPanier = $cartService->getFullCart();  
        $total = $cartService->getTotal();

        $reclamations=$rep->findById($value);
        $donnees = $paginator->paginate(
            $reclamations,
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            2 // Nombre de résultats par page
        );



        $ligneCommande = $ligneCommande->findall();
        return $this->render('reclamation/listReclamation.html.twig', [
            'tab' => $donnees,
            'elements' => $dataPanier,
            'tab1' => $ligneCommande,
            'total' => $total
        ]);
    }

    
    /** 
     * @Route("reclamation/etat/{id}", name="etat_rec")
     */
    public function afficheReponse($id, ReponseRepository $rep,CartService $cartService): Response
    {
        $dataPanier = $cartService->getFullCart();  
        $total = $cartService->getTotal();
        
       $reponse=$rep->find($id);
       #$reponseMessage=$reclamation->getReclamation();

        return $this->render('reclamation/reponse.html.twig', [
            'tab' => $reponse,
            'elements' => $dataPanier,
            'total' => $total
        ]);
    }

      /** 
     * @Route("reclamation/true", name="etat_true")
     */
    public function affichetrue(CartService $cartService): Response
    {
        $dataPanier = $cartService->getFullCart();  
        $total = $cartService->getTotal();
        
        return $this->render('reclamation/true.html.twig', [
            'elements' => $dataPanier,
            'total' => $total
        ]);
    }
      /** 
     * @Route("reclamation/error", name="etat_error")
     */
    public function afficheErreur(CartService $cartService): Response
    {
        $dataPanier = $cartService->getFullCart();  
        $total = $cartService->getTotal();
        
        return $this->render('reclamation/error.html.twig', [
            'elements' => $dataPanier,
            'total' => $total
        ]);
    }

     /**
     * @Route("/reclamation/searchajax", name="ajaxsearchRec",methods={"GET"})
     */
    public function search(Request $request,ReclamationRepository $rep, LigneCommandeRepository $ligneCommande)
    {
        $str1 = $request->get('searchValue');
        

        $result = $rep->searchRec($str1);
        return $this->render('reclamation/reclamationAjax.html.twig', [
            "tab" => $result,
            'tab1' => $ligneCommande,
        ]);
    }


    


    




}
