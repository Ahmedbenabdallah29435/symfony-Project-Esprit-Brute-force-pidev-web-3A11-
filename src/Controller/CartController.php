<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Services\cart\CartService;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

 /**
     * @Route("/cart", name="cart_")
     */
class CartController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(CartService $cartService): Response
    {  
        $dataPanier = $cartService->getFullCart();  
        $total = $cartService->getTotal();
    
        if ($total == 0 )
        {
            $this->addFlash('error','Veuillez remplir le panier');

            return $this->render('cart/index.html.twig', [
                'elements' => $dataPanier,
                'total' => $total,
            ]);
        }
        else
        {
        return $this->render('cart/index.html.twig', [
            'elements' => $dataPanier,
            'total' => $total,
        ]);
        }
    }
    /**
     * @Route("/add/{id}", name="add")
     */
    public function add($id,CartService $cartService)
    {

     $cartService->add($id);
    return $this->redirecttoRoute("cart_index");

    }

     /**
     * @Route("/add1/{id}", name="add1")
     */
    public function add1($id,CartService $cartService)
    {

     $panier = $cartService->add($id);
  

    return new  Response(json_encode($panier));


    }



    /**
     * @Route("/remove/{id}", name="remove")
     */
    public function remove(Produit $produit, SessionInterface $session)
    {
       // On récupère le panier actuel
    $panier = $session->get("panier", []);
    $id= $produit->getId();

    if(!empty($panier[$id])){
       if($panier[$id]>1){
           $panier[$id]-- ; 
    }else{
        unset($panier[$id]);
    }}
    

    $session->set("panier", $panier);
    
    return $this->redirecttoRoute("cart_index");
    }

     /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete($id,CartService $cartService)
    {
       
    $cartService->delete($id);
    return $this->redirecttoRoute("cart_index");
    }

     /**
     * @Route("/delete1/{id}", name="delete1")
     */
    public function delete1($id,CartService $cartService)
    {
       
    $cartService->delete($id);
    return $this->redirecttoRoute("home");
    }

    

    /**
     * @Route("/deleteall", name="deleteall")
     */
    public function deleteall( SessionInterface $session)
    {
    
        $session->remove("panier");
        
    return $this->redirecttoRoute("cart_index");
    }

#################### Mobile ##################
    /**
     * @Route("/addjson", name="add")
     */
    public function addjson(Request $request, Produitrepository $rep , SerializerInterface $serializer)
    {

    $id = $request->get("id");
    

    // On récupère le panier actuel
    $find= $rep->find($id);



    
    $json = $serializer->serialize($find, 'json', ['groups' => 'produit:read']);
        return new JsonResponse($json, 200, [], true);


    }

    /**
     * @Route("/affcartjson", name="index_json")
     */
    public function indexjson(SessionInterface $session, ProduitRepository $produitrep): Response
    {
        $panier = $session->get("panier",[]);
        
        // on fabrique les données 

        $dataPanier = []; 
        $total = 0; 

        foreach ($panier as $id => $quantite){
            $produit = $produitrep->find($id);
            $dataPanier[] = [
                "id" => $produit->getId(),
                "nom" => $produit->getNomProduit(),
                "prix" => $produit->getPrixProduit(),
                "image" => $produit->getImage(),
                "quantite" => $quantite  
            ];
        }
      
         
        return new  Response(json_encode($dataPanier));

      
    }




}

