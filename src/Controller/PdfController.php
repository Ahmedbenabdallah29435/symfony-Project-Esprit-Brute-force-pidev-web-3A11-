<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Repository\CommandeRepository;
use App\Repository\LivraisonRepository;
use App\Repository\LigneCommandeRepository;

// Include Dompdf required namespaces
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class PdfController extends AbstractController
{
    /**
     * @Route("/pdf/{id}", name="pdf")
     */
    public function index($id,CommandeRepository $rep,LigneCommandeRepository $ligneCommande): Response
    {

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);
        $pdfOptions->setIsHtml5ParserEnabled(true);
 
    
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $dompdf->getOptions()->getChroot(); // something like 'C:\\laragon\\www\\your-local-website\\vendor\\dompdf\\dompdf'
        


        $commande=$rep->find($id);
        $ligneCommande = $ligneCommande->findByidclient($id);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('commande/pdf.html.twig', [
            'tab' => $commande,
            'tab1' => $ligneCommande

        ]);
        
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');



        // Render the HTML as PDF
        $dompdf->render();
        
        // Output the generated PDF to Browser (force download)
        $dompdf->stream("facture.pdf", [
            "Attachment" => true
        ]);

    }

    /**
     * @Route("/bandelivraison/{id}", name="bandelivraison")
     */
    public function bande($id,LivraisonRepository $livrepo ,CommandeRepository $commanderepo,LigneCommandeRepository $lignerepo): Response
    {

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);
        $pdfOptions->setIsHtml5ParserEnabled(true);
 
    
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $dompdf->getOptions()->getChroot(); // something like 'C:\\laragon\\www\\your-local-website\\vendor\\dompdf\\dompdf'
        


        $livraision=$livrepo->find($id);
        $ligneCommande = $lignerepo->findAll();


        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('dashboard/livraison/pdf.html.twig', [
            'tab' => $livraision,
            'tab1' => $ligneCommande,

        ]);
        
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');



        // Render the HTML as PDF
        $dompdf->render();
        
        // Output the generated PDF to Browser (force download)
        $dompdf->stream("facture.pdf", [
            "Attachment" => true
        ]);

    }
       
      
    
}
