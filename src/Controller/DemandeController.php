<?php

namespace App\Controller;

use App\Entity\Demandes;
use App\Form\ActeurType;
use App\Services\cart\CartService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DemandeController extends AbstractController
{
    /**
     * @Route("/demande", name="demande")
     */
    public function index(CartService $cartService): Response
    {
        $dataPanier = $cartService->getFullCart();  
        $total = $cartService->getTotal();

        return $this->render('demande/index.html.twig', [
            'controller_name' => 'DemandeController',
            'total' => $total,
            'elements' => $dataPanier
            
        ]);
    }
    /**
     * @Route("/demande/addActeur",name="acteurSAdd")
     */

    public function AddActeur(CartService $cartService,Request $request , UserPasswordEncoderInterface $encoder){
        $dataPanier = $cartService->getFullCart();  
        $total = $cartService->getTotal();

        $em = $this->getDoctrine()->getManager();
        $acteur= new Demandes();
        $form=$this ->createForm(ActeurType::class,$acteur);
        $form->add('Register', SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {

            $hash = $encoder->encodePassword($acteur, $acteur->getPassword());
            $acteur->setPassword($hash);

            $acteur->setEtat("Disponible");

            $em=$this->getDoctrine()->getManager();
            $em->persist($acteur);
            $em->flush();
            return $this->redirectToRoute('home');

        }

        return $this->render('demande/addActeur.html.twig', [
            'ActeurForm'=>$form->createView(),
            'total' => $total,
            'elements' => $dataPanier
        ]);
    }

}
