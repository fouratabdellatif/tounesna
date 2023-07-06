<?php

namespace App\Controller;

use App\Entity\RateHotel;
use App\Form\RateHotelType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/rate/hotel')]
class RateHotelController extends AbstractController
{
    #[Route('/', name: 'app_rate_hotel_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $rateHotels = $entityManager
            ->getRepository(RateHotel::class)
            ->findAll();

        return $this->render('rate_hotel/index.html.twig', [
            'rate_hotels' => $rateHotels,
        ]);
    }

    #[Route('/new', name: 'app_rate_hotel_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $rateHotel = new RateHotel();
        $form = $this->createForm(RateHotelType::class, $rateHotel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($rateHotel);
            $entityManager->flush();

            return $this->redirectToRoute('app_rate_hotel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rate_hotel/new.html.twig', [
            'rate_hotel' => $rateHotel,
            'form' => $form,
        ]);
    }

    #[Route('/{idRate}', name: 'app_rate_hotel_show', methods: ['GET'])]
    public function show(RateHotel $rateHotel): Response
    {
        return $this->render('rate_hotel/show.html.twig', [
            'rate_hotel' => $rateHotel,
        ]);
    }

    #[Route('/{idRate}/edit', name: 'app_rate_hotel_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RateHotel $rateHotel, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RateHotelType::class, $rateHotel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_rate_hotel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rate_hotel/edit.html.twig', [
            'rate_hotel' => $rateHotel,
            'form' => $form,
        ]);
    }

    #[Route('/{idRate}', name: 'app_rate_hotel_delete', methods: ['POST'])]
    public function delete(Request $request, RateHotel $rateHotel, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rateHotel->getIdRate(), $request->request->get('_token'))) {
            $entityManager->remove($rateHotel);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_rate_hotel_index', [], Response::HTTP_SEE_OTHER);
    }
}
