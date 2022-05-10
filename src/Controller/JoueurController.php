<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Form\JoueurType;
use App\Repository\JoueurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use function PHPUnit\Framework\isEmpty;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

    /**
     * @Route("/r/search_rec", name="search_joueur", methods={"GET"})
     */
    public function search_rec(Request $request,NormalizerInterface $Normalizer,JoueurRepository $JoueurRepository ): Response
    {

        $requestString=$request->get('searchValue');
        $requestTrie=$request->get('orderid');

        //   dump($requestString);
        //  dump($requestString2);
        $joueur = $JoueurRepository->SearchAndTriJoueur($requestString,$requestTrie);
        //   dump($joueurs);
        $jsoncontentc =$Normalizer->normalize($joueur,'json',['groups'=>'posts:read']);
        //  dump($jsoncontentc);
        $jsonc=json_encode($jsoncontentc);
        //   dump($jsonc);
        if(  $jsonc == "[]" )
        {
            return new Response(null);
        }
        else{        return new Response($jsonc);
        }

    }


    public function getData() :array
    {
        /**
         * @var $Joueur rec[]
         */
        $list = [];
        $joueur = $this->getDoctrine()->getRepository(Joueur::class)->findAll();

        foreach ($joueur as $rec) {
            $list[] = [
                $rec->getIdjoueur(),
                $rec->getNomjoueur(),
                $rec->getPrenomjoueur(),
                $rec->getDatedenaissance(),
                $rec->getAge(),
                $rec->getSexe(),
                $rec->getVille(),
                $rec->getImgjoueur(),
             //   $rec->getCategorie()
            ];
        }
        return $list;
    }

    /**
     * @Route("/excel/export",  name="export")
     */
    public function export()
    {
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('Joueur List');

        $sheet->getCell('A1')->setValue('idJoueur');
        $sheet->getCell('B1')->setValue('NomJoueur');
        $sheet->getCell('C1')->setValue('PrenomJoueur');
        $sheet->getCell('D1')->setValue('DateDeNaissance');
        $sheet->getCell('E1')->setValue('Age');
        $sheet->getCell('F1')->setValue('Sexe');
        $sheet->getCell('G1')->setValue('Ville');
        $sheet->getCell('H1')->setValue('imgJoueur');
        //$sheet->getCell('I1')->setValue('Categorie');

        // Increase row cursor after header write
        $sheet->fromArray($this->getData(),null, 'A2', true);
        $writer = new Xlsx($spreadsheet);//Xlsx  SheetJS Spreadsheet data parser and writer.
        // $writer->save('ss.xlsx');
        $writer->save('Joueur'.date('m-d-Y_his').'.xlsx');
        return $this->redirectToRoute('app_joueur_backindex');

    }

    /**
     * @Route("/r/joueur_stat", name="joueur_stat", methods={"GET"})
     */
    public function joueur_stat(JoueurRepository $JoueurRepository): Response
    {
        $nbrs[]=Array();

        $e1=$JoueurRepository->find_bySexe("Homme");
        dump($e1);
        $nbrs[]=$e1[0][1];
        $e2=$JoueurRepository->find_bySexe("Femme");
        dump($e2);
        $nbrs[]=$e2[0][1];

        dump($nbrs);
        reset($nbrs);
        dump(reset($nbrs));
        $key = key($nbrs);
        dump($key);
        dump($nbrs[$key]);

        unset($nbrs[$key]);

        $nbrss=array_values($nbrs);
        dump(json_encode($nbrss));

        return $this->render('joueur/stat.html.twig', [
            'nbr' => json_encode($nbrss),
        ]);
    }
}
