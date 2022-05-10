<?php

namespace App\Controller;


use App\Entity\Reponse;
use App\Entity\Reclamation;
use App\Entity\Utilisateurs;
use Doctrine\ORM\Mapping\Id;
use App\Form\ReclamationType;
use App\Services\cart\CartService;
use App\Repository\ReponseRepository;
use App\Repository\CommandeRepository;
use App\Repository\ReclamationRepository;
use App\Repository\UtilisateursRepository;
use App\Repository\LigneCommandeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Serializer;

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
        {$em=$this->getDoctrine()->getManager();
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
    public function afficher($value, ReclamationRepository $rep,CartService $cartService,LigneCommandeRepository $ligneCommande): Response
    {    $dataPanier = $cartService->getFullCart();  
        $total = $cartService->getTotal();

        $reclamations=$rep->findById($value);
        $ligneCommande = $ligneCommande->findall();
        return $this->render('reclamation/listReclamation.html.twig', [
            'tab' => $reclamations,
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


    // /**
    // * @Route("/AfficheReclamationjson/",name="AfficheReclamationjson")
    // */
    // public function allRecAction()
    // {

    //     $reclamation = $this->getDoctrine()->getManager()->getRepository(Reclamation::class)->findAll();
    //     $serializer = new Serializer([new ObjectNormalizer()]);
    //     $formatted = $serializer->normalize($reclamation);

    //     return new JsonResponse($formatted);

    // }


    /**
    * @Route("/addreclamationJSON/",name="addreclamationJSON")
    */

    public function addReclamationJSON(Request $request,NormalizerInterface $Normalizer,CommandeRepository $cc ,ReclamationRepository $rep, UtilisateursRepository $rep1,SerializerInterface $serializer)
    {
        $em = $this->getDoctrine()->getManager();
        $reclamation = new Reclamation();
        $reclamation->setName($request->get('name'));
        $reclamation->setSubject($request->get('subject'));
        $reclamation->setMessage($request->get('message'));
        $reclamation->setType($request->get('type'));
        $reclamation->setEtat("En cours");
        $reclamation->setEmail($request->get('email'));

        $id_client = $request->get('idclient');
        $idc = $rep1->find($id_client);
        $reclamation->setClient($idc);

        $idrec= $request->get('idrec');
        $idreclamation = $cc->find($idrec);
        $reclamation->setCommande($idreclamation);
        
        /*$Iduser = $rep1->getId();
        $cl = new Utilisateurs();
        //$cl = $rep1->find($Iduser);
        $cl = $rep1 ->findBy(array('client' => $Iduser));

        $reclamation->setClient($cl);*/
       



        $em->persist($reclamation);
        $em->flush();
      

        $json = $serializer->serialize($reclamation, 'json', ['groups' => ['normal']]);
        return new JsonResponse($json, 200, [], true);

    }

    /**
     * @Route("/deleteReclamation", name="delete_reclamation")
     */

    public function deleteReclamationAction(Request $request) {
        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $reclamation = $em->getRepository(Reclamation::class)->find($id);
        if($reclamation!=null ) {
            $em->remove($reclamation);
            $em->flush();

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("Reclamation a ete supprimee avec success.");
            return new JsonResponse($formatted);

        }
        return new JsonResponse("id reclamation invalide.");


    }

    /**
     * @Route("/updateReclamation", name="update_reclamation")
     * @Method("PUT")
     */
    public function modifierReclamationAction(Request $request,SerializerInterface $serializer) {
        $em = $this->getDoctrine()->getManager();
        $reclamation = $this->getDoctrine()->getManager()
            ->getRepository(Reclamation::class)
            ->find($request->get("id"));


        $reclamation->setName($request->get('name'));
        $reclamation->setSubject($request->get('subject'));
        $reclamation->setMessage($request->get('message'));
        $reclamation->setType($request->get('type'));
        $reclamation->setEtat("En cours");
        $reclamation->setEmail($request->get('email'));

        $em->persist($reclamation);
        $em->flush();

        $json = $serializer->serialize($reclamation, 'json', ['groups' => ['normal']]);
        return new JsonResponse($json, 200, [], true);

        

    }










}