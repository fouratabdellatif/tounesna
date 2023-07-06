<?php

namespace App\Controller;

use App\Entity\Hotel;
use App\Form\HotelType;
use App\Repository\HotelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/hotel')]
class HotelAdminController extends AbstractController
{
    #[Route('/search', name: 'hotel_search')]
    public function search(Request $request, HotelRepository $hotelRepository): Response
    {
        $query = $request->query->get('q');
        $hotels = $hotelRepository->findByNom($query);

        return $this->render('hotelAdmin/search.html.twig', [
            'hotels' => $hotels,
            'query' => $query,
        ]);
    }
    
    #[Route('/', name: 'admin_hotel_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, Request $request, HotelRepository $hotelRepository): Response
    {
        $hotels = $entityManager
            ->getRepository(Hotel::class)
            ->findAll();
        $query = $request->query->get('q');
        $hotels = $hotelRepository->findByNom($query);

        return $this->render('hotelAdmin/index.html.twig', [
            'hotels' => $hotels,
            'query' => $query,
        ]);
    }

    #[Route('/new', name: 'admin_hotel_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $hotel = new Hotel();
        $form = $this->createForm(HotelType::class, $hotel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $request->files->get('hotel')['image'];
            $uploads_directory = $this->getParameter('uploads_directory');
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($uploads_directory, $filename);
            $hotel->setImage($filename);
            $entityManager->persist($hotel);
            $entityManager->flush();

            return $this->redirectToRoute('admin_hotel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('hotelAdmin/new.html.twig', [
            'hotel' => $hotel,
            'form' => $form,
        ]);
    }

    #[Route('/{idh}', name: 'admin_hotel_show', methods: ['GET'])]
    public function show(Hotel $hotel): Response
    {
        return $this->render('hotelAdmin/show.html.twig', [
            'hotel' => $hotel,
        ]);
    }

    #[Route('/{idh}/edit', name: 'admin_hotel_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Hotel $hotel, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(HotelType::class, $hotel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $request->files->get('hotel')['image'];
            $uploads_directory = $this->getParameter('uploads_directory');
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($uploads_directory, $filename);
            $hotel->setImage($filename);
            $entityManager->persist($hotel);
            $entityManager->flush();

            return $this->redirectToRoute('admin_hotel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('hotelAdmin/edit.html.twig', [
            'hotel' => $hotel,
            'form' => $form,
        ]);
    }

    #[Route('/{idh}', name: 'admin_hotel_delete', methods: ['POST'])]
    public function delete(Request $request, Hotel $hotel, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$hotel->getIdh(), $request->request->get('_token'))) {
            $entityManager->remove($hotel);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_hotel_index', [], Response::HTTP_SEE_OTHER);
    }
}
