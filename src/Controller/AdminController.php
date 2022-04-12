<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/evenement", name="app_evenement")
     */
    public function index(): Response
    {
        return $this->render('evenement/index.html.twig', [
            'controller_name' => 'EvenementController',
        ]);
    }

   /**
     * @Route("/home", name="display_front")
     */
    public function indexF(): Response
    {
        return $this->render('Front/index.html.twig'    
        );
    }
    /**
     * @Route("/back", name="display_back")
     */
    public function indexB(): Response
    {
        return $this->render('Back/index.html.twig'    
        );
    }
}