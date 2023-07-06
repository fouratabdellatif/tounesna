<?php

namespace App\Controller;

use App\Entity\RateHotel;
use App\Entity\Utilisateur;
use App\Form\RateHotelType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/rate/hotel')]
class RateHotelAdminController extends AbstractController
{
    #[Route('/', name: 'admin_rate_hotel_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $rateHotels = $entityManager
            ->getRepository(RateHotel::class)
            ->findAll();

        $users = $entityManager
            ->getRepository(Utilisateur::class)
            ->findAll();

        return $this->render('rate_hotelAdmin/index.html.twig', [
            'rate_hotels' => $rateHotels,
            'users' => $users,
        ]);
    }

    #[Route('/{idRate}', name: 'admin_rate_hotel_show', methods: ['GET'])]
    public function show(RateHotel $rateHotel): Response
    {
        return $this->render('rate_hotelAdmin/show.html.twig', [
            'rate_hotel' => $rateHotel,
        ]);
    }
}
