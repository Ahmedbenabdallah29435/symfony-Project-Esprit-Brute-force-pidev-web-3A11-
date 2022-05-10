<?php

namespace App\Controller;

use Stripe\Checkout\Session;
use App\Services\cart\CartService;
use App\Repository\ProduitRepository;
use App\Repository\BoutiqueRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class PaymentController extends AbstractController
{
    /**
     * @Route("/payment", name="payment")
     */
    public function index(): Response
    {
        return $this->render('payment/index.html.twig', [
            'controller_name' => 'PaymentController',
        ]);
    }

     /**
     * @Route("/checkout", name="checkout")
     */
    public function chekout(CartService $cartService): Response
    {
       
        \Stripe\Stripe::setApiKey('sk_test_51KXAogH5r3kab54rJEMq45B4y8znxihxXppqWZR29QZiV1zxi4q9u889j2WfPVICKA5Dmf7RwXbes2Mh6Ec8EOeJ00X1EUnnxk');
       
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
              'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                  'name' => 'Total Commande',
                ],
                'unit_amount' => $cartService->getTotal()*100,
              ],
              'quantity' => 1,
            ]],
                'mode' => 'payment',
            'success_url' => $this->generateUrl('success', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('error', [], UrlGeneratorInterface::ABSOLUTE_URL),
          ]);
          return $this->redirect($session->url);

    }


     /**
     * @Route("/error", name="error")
     */
    public function error(CartService $cartService,ProduitRepository $rep, BoutiqueRepository $repp)
    {
      $dataPanier = $cartService->getFullCart();  
      $total = $cartService->getTotal();

      $produits=$rep->findAll();
        $boutiques=$repp->findAll();

      $this->addFlash('error','Failed payment');
        return $this->render('cart/index.html.twig', [
          'elements' => $dataPanier,
          'total' => $total,
          'tabprod' => $produits,
          'tabbout' => $boutiques
      ]);
    }

     /**
     * @Route("/success", name="success")
     */
    public function success(CartService $cartService,ProduitRepository $rep, BoutiqueRepository $repp)
    {
      $dataPanier = $cartService->getFullCart();  
      $total = $cartService->getTotal();

      $produits=$rep->findAll();
        $boutiques=$repp->findAll();

      $this->addFlash('success','Successful payment');
        return $this->render('front/home.html.twig', [
            'elements' => $dataPanier,
            'total' => $total,
            'tabprod' => $produits,
            'tabbout' => $boutiques
        ]);
    }
        
  
}
