<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use App\Repository\ProduitRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use DateTime;
use Dompdf\Dompdf;
use Dompdf\Options;

#[Route('/commande')]
class CommandeController extends AbstractController
{
    #[Route('/', name: 'app_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository, UtilisateurRepository $userRepo): Response
    {
        $commandes = $commandeRepository->findAll();
        $user = $userRepo->find(21);

        foreach ($commandes as $key => $commande) {
            if ($commande->getUser() != $user) {
                unset($commandes[$key]);
            }
        }
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    #[Route('/new', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CommandeRepository $commandeRepository): Response
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandeRepository->save($commande, true);

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande, CommandeRepository $commandeRepository): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandeRepository->save($commande, true);

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_delete', methods: ['POST'])]
    public function delete(Request $request, Commande $commande, CommandeRepository $commandeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {
            $commandeRepository->remove($commande, true);
        }

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }

    

    /**
     * @param Request $request
     * @param SessionInterface $session
     * @return Response
     * @Route ("/panier/addcommande", name="add_commande", methods={"GET","POST"})
     */

     function addCommande(SessionInterface $session, ProduitRepository $repo, CommandeRepository $commandeRepository, UtilisateurRepository $userRepo)
     {
         $panier = $session->get("panier", []);
         $dataPanier = [];
         $total = 0;
         $produits = [];
 
         foreach ($panier as $id => $qty) {
             $produit = $repo->find($id);
             if ($qty > 0) {
                 $dataPanier[] = [
                     "produit" => $produit,
                     "qty" => $qty
                 ];
                 $total += $produit->getPrice() * $qty;
 
                 $user = $userRepo->find(21);
                 $commande = new Commande();
                 $commande->setProduit($produit);
                 $commande->setUser($user);
                 $commande->setProductqty($qty);
 
                 array_push($produits, $commande);
             }
         }
 
         // dd($dataPanier);
         $s = 0;
 
         for ($i = 0; $i < count($dataPanier); $i++) {
             $s = $s + 1;
         }
         return $this->render('commande/commande.html.twig', ['s', 'dataPanier' => $dataPanier, 'total' => $total]);
     }
 
 
     /**
      * @param Request $request
      * @param SessionInterface $session
      * @return Response
      * @Route ("/panier/confirmcommande", name="confirm_commande", methods={"GET","POST"})
      */
 
     function confirmCommande(SessionInterface $session, ProduitRepository $repo, CommandeRepository $commandeRepository, UtilisateurRepository $userRepo)
     {
         $panier = $session->get("panier", []);
         $dataPanier = [];
         $total = 0;
         $produits = [];
         $now = new DateTime();
 
         foreach ($panier as $id => $qty) {
             $produit = $repo->find($id);
             if ($qty > 0) {
                 $dataPanier[] = [
                     "produit" => $produit,
                     "qty" => $qty
                 ];
                 $total += $produit->getPrice() * $qty;
                //  $user1 = $this->get('security.token_storage')->getToken()->getUser();
 
                 $user = $userRepo->find(21);
                 $commande = new Commande();
                 $commande->setNum(random_int(1000, 9999));
                 $commande->setProduit($produit);
                 $commande->setUser($user);
                 $commande->setProductqty($qty);
                 $commande->setDate($now);
         
                 $commandeRepository->save($commande, true);
 
                 array_push($produits, $commande);
             }
         }
 
         $panier = $session->set("panier", []);
         $dataPanier = $panier;
 
 
         return $this->redirectToRoute('displaypanier', [], Response::HTTP_SEE_OTHER);
 
         $s = 0;
 
         //for ($i = 0; $i < count($dataPanier); $i++) {
          //   $s = $s + 1;
        // }
         // dd($s);
         return $this->render('commande/commande.html.twig', ['s', 'dataPanier' => $dataPanier, 'total' => $total, 'panier' => $panier]);
     }
    
     #[Route ('/printcommande/{id}', name: 'app_print_commande')]
     public function exportCommandePDF($id, CommandeRepository $repo)
     {
         // On définit les options du PDF
         $pdfOptions = new Options();
         // Police par défaut
         $pdfOptions->set('defaultFont', 'Arial');
         $pdfOptions->setIsRemoteEnabled(true);
 
         // On instancie Dompdf
         $dompdf = new Dompdf();
         $context = stream_context_create([
             'ssl' => [
                 'verify_peer' => FALSE,
                 'verify_peer_name' => FALSE,
                 'allow_self_signed' => TRUE
             ]
         ]);
         $dompdf->setHttpContext($context);
         $commande = $repo->find($id);
         // dd($commandes);
 
         // On génère le html
         $html = $this->renderView(
             'commande/print.html.twig',
             [
                 'commande' => $commande
             ]
         );
 
         $dompdf->loadHtml($html);
         $dompdf->setPaper('A4', 'portrait');
         $dompdf->render();
 
         // On génère un nom de fichier
         $fichier = 'commande'. $commande->getNum() . date('c') .'.pdf';
 
         // On envoie le PDF au navigateur
         $dompdf->stream($fichier, [
             'Attachment' => true
         ]);
 
         return new Response();
     }
 
     #[Route ('/printallcommandes/{id}', name: 'app_print_commandes')]
     public function exportAllCommandesPDF(CommandeRepository $repo)
     {
         // On définit les options du PDF
         $pdfOptions = new Options();
         // Police par défaut
         $pdfOptions->set('defaultFont', 'Arial');
         $pdfOptions->setIsRemoteEnabled(true);
 
         // On instancie Dompdf
         $dompdf = new Dompdf($pdfOptions);
         $context = stream_context_create([
             'ssl' => [
                 'verify_peer' => FALSE,
                 'verify_peer_name' => FALSE,
                 'allow_self_signed' => TRUE
             ]
         ]);
         $dompdf->setHttpContext($context);
         $commandes = $repo->findAll();
         // dd($commandes);
 
         // On génère le html
         $html = $this->renderView(
             'commande/printall.html.twig',
             [
                 'commandes' => $commandes
             ]
         );
 
         $dompdf->loadHtml($html);
         $dompdf->setPaper('A4', 'portrait');
         $dompdf->render();
 
         // On génère un nom de fichier
         $fichier = 'commandes'. date('c') .'.pdf';
 
         // On envoie le PDF au navigateur
         $dompdf->stream($fichier, [
             'Attachment' => true
         ]);
 
         return new Response();
     }
}
