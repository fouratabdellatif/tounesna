<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Utilisateur;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/evenement')]
class EvenementAdminController extends AbstractController
{
    #[Route('/sortByAscDate', name: 'sort_by_asc_date')]
    public function sortAscDate(EntityManagerInterface $entityManager, EvenementRepository $evenementRepository, Request $request)
    {
        $evenements = $entityManager
            ->getRepository(Evenement::class)
            ->findAll();

        $query = $request->query->get('q');
        $evenements = $this->getDoctrine()
            ->getRepository(Evenement::class)
            ->findByNom($query);

        $evenements = $evenementRepository->sortByAscDate();
    
        return $this->render("evenementAdmin/index.html.twig",[
            'evenements' => $evenements,
            'query' => $query,
        ]);
    }
    
    #[Route('/sortByDescDate', name: 'sort_by_desc_date')]
    public function sortDescDate(EntityManagerInterface $entityManager, EvenementRepository $evenementRepository, Request $request)
    {
        $evenements = $entityManager
            ->getRepository(Evenement::class)
            ->findAll();

        $query = $request->query->get('q');
        $evenements = $this->getDoctrine()
            ->getRepository(Evenement::class)
            ->findByNom($query);

        $evenements = $evenementRepository->sortByDescDate();
    
        return $this->render("evenementAdmin/index.html.twig",[
            'evenements' => $evenements,
            'query' => $query,
        ]);
    }

    #[Route('/search', name: 'evenement_search')]
    public function search(Request $request, EvenementRepository $evenementRepository): Response
    {
        $query = $request->query->get('q');
        $evenements = $evenementRepository->findByNom($query);

        return $this->render('evenementAdmin/search.html.twig', [
            'evenements' => $evenements,
            'query' => $query,
        ]);
    }

    #[Route('/', name: 'admin_evenement_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, Request $request, EvenementRepository $evenementRepository): Response
    {
        $evenements = $entityManager
            ->getRepository(Evenement::class)
            ->findAll();
        $query = $request->query->get('q');
        $evenements = $evenementRepository->findByNom($query);

        return $this->render('evenementAdmin/index.html.twig', [
            'evenements' => $evenements,
            'query' => $query,
        ]);
    }

    #[Route('/new', name: 'admin_evenement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $evenement = new Evenement();
        // $user = $entityManager->getRepository(Utilisateur::class)->find(21);
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $request->files->get('evenement')['image'];
            $uploads_directory = $this->getParameter('uploads_directory');
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($uploads_directory, $filename);
            $evenement->setImage($filename);
            // $evenement->setAuteur($user);
            $entityManager->persist($evenement);
            $entityManager->flush();

            return $this->redirectToRoute('admin_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evenementAdmin/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{idev}', name: 'admin_evenement_show', methods: ['GET'])]
    public function show(Evenement $evenement): Response
    {
        return $this->render('evenementAdmin/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    #[Route('/{idev}/edit', name: 'admin_evenement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $request->files->get('evenement')['image'];
            $uploads_directory = $this->getParameter('uploads_directory');
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($uploads_directory, $filename);
            $evenement->setImage($filename);
            // $evenement->setAuteur($user);
            $entityManager->persist($evenement);
            $entityManager->flush();

            return $this->redirectToRoute('admin_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evenementAdmin/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{idev}', name: 'admin_evenement_delete', methods: ['POST'])]
    public function delete(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getIdev(), $request->request->get('_token'))) {
            $entityManager->remove($evenement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_evenement_index', [], Response::HTTP_SEE_OTHER);
    }
}
