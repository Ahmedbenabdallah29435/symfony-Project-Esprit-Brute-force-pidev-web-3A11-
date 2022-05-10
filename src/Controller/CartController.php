<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Services\cart\CartService;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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


}