<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Form\JoueurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function PHPUnit\Framework\isEmpty;

/**
 * @Route("/joueur")
 */
class JoueurController extends AbstractController
{
    /////////////////////////////////////////////////
    /**
     * @Route("/newback", name="app_joueur_backnew", methods={"GET", "POST"})
     */
    public function newback(Request $request, EntityManagerInterface $entityManager): Response
    {

        $joueur = new Joueur();
        $form = $this->createForm(JoueurType::class, $joueur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /////
            // On récupère les images transmises
            $file = $form->get('imgjoueur')->getData();
            if ($file !== null) {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                try {
                    $file->move(
                        $this->getParameter('images_directory'),
                        $fileName
                    );
                } catch (FileException $e) {

                    // ... handle exception if something happ

                }
                $joueur->setImgjoueur($fileName);
            }
            /////
            /////

            $entityManager->persist($joueur);
            $entityManager->flush();

            return $this->redirectToRoute('app_joueur_backindex', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('joueur/BackNew.html.twig', [
            'joueur' => $joueur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idjoueur}", name="app_joueur_backdelete", methods={"POST"})
     */
    public function deletebackjoueur(Request $request, Joueur $joueur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$joueur->getIdjoueur(), $request->request->get('_token'))) {
            $entityManager->remove($joueur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_joueur_backindex', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/backindex", name="app_joueur_backindex", methods={"GET"})
     */
    public function backindex(EntityManagerInterface $entityManager): Response
    {
        $joueur = $entityManager
            ->getRepository(Joueur::class)
            ->findAll();

        return $this->render('joueur/Backindex.html.twig', [
            'joueur' => $joueur,
        ]);
    }


    /**
     * @Route("/{idjoueur}/backedit", name="app_joueur_backedit", methods={"GET", "POST"})
     */
    public function backedit(Request $request, Joueur $joueur, EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(JoueurType::class, $joueur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            // On récupère les images transmises
            $file = $form->get('imgjoueur')->getData();
            if ($file !== null) {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                try {
                    $file->move(
                        $this->getParameter('images_directory'),
                        $fileName
                    );
                } catch (FileException $e) {

                    // ... handle exception if something happ

                }
                $joueur->setImgjoueur($fileName);
            }


            $entityManager->flush();

            return $this->redirectToRoute('app_joueur_backindex', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('joueur/BackEdit.html.twig', [
            'joueur' => $joueur,
            'form' => $form->createView(),
        ]);

    }


    /////////////////////////////////////// FRONT

    /**
     * @Route("/", name="app_joueur_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $joueur = $entityManager
            ->getRepository(Joueur::class)
            ->findAll();

        $joueurss = new Joueur();
        $form = $this->createForm(JoueurType::class, $joueurss);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /////
            // On récupère les images transmises
            $file = $form->get('imgjoueur')->getData();
            if ($file !== null) {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                try {
                    $file->move(
                        $this->getParameter('images_directory'),
                        $fileName
                    );
                } catch (FileException $e) {

                    // ... handle exception if something happ

                }
                $joueurss->setImgjoueur($fileName);
            }
            /////
            /////

            $entityManager->persist($joueurss);
            $entityManager->flush();

            return $this->redirectToRoute('app_joueur_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('joueur/new.html.twig', [
            'joueur' => $joueur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idjoueur}", name="app_joueur_show", methods={"GET"})
     */
    public function show(Joueur $joueur): Response
    {
        return $this->render('joueur/show.html.twig', [
            'joueur' => $joueur,
        ]);
    }

    /**
     * @Route("/{idjoueur}/edit", name="app_joueur_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Joueur $joueur, EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(JoueurType::class, $joueur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            // On récupère les images transmises
            $file = $form->get('imgjoueur')->getData();
            if ($file !== null) {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                try {
                    $file->move(
                        $this->getParameter('images_directory'),
                        $fileName
                    );
                } catch (FileException $e) {

                    // ... handle exception if something happ

                }
                $joueur->setImgjoueur($fileName);
            }


            $entityManager->flush();

            return $this->redirectToRoute('app_joueur_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('joueur/edit.html.twig', [
            'joueur' => $joueur,
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/{idjoueur}", name="app_joueur_delete", methods={"POST"})
     */
    public function delete(Request $request, Joueur $joueur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$joueur->getIdjoueur(), $request->request->get('_token'))) {
            $entityManager->remove($joueur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_joueur_new', [], Response::HTTP_SEE_OTHER);
    }


}
