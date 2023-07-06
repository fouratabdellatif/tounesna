<?php

namespace App\Controller;

use App\Entity\Gouvernorat;
use App\Entity\Hotel;
use App\Entity\Activities;
use App\Entity\Plat;
use App\Entity\Evenement;
use App\Entity\Produit;
use App\Entity\Commentaire;
use App\Entity\Utilisateur;
use App\Entity\RateHotel;
use App\Form\GouvernoratType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/gouvernorat')]
class GouvernoratController extends AbstractController
{
    #[Route('/', name: 'gouvernorats', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $gouvernorats = $entityManager
            ->getRepository(Gouvernorat::class)
            ->findAll();

        return $this->render('gouvernorat/index.html.twig', [
            'gouvernorats' => $gouvernorats,
        ]);
    }

    #[Route('/{idGouver}', name: 'app_gouvernorat_show', methods: ['GET'])]
    public function show(Gouvernorat $gouvernorat, EntityManagerInterface $entityManager): Response
    {
        $hotels = $entityManager
            ->getRepository(Hotel::class)
            ->findAll();
        $plats = $entityManager
            ->getRepository(Plat::class)
            ->findAll();
        $activities = $entityManager
            ->getRepository(Activities::class)
            ->findAll();
        $events = $entityManager
            ->getRepository(Evenement::class)
            ->findAll();
        $produits = $entityManager
            ->getRepository(Produit::class)
            ->findAll();
        $commentaires = $entityManager
            ->getRepository(Commentaire::class)
            ->findAll();
        $users = $entityManager
            ->getRepository(Utilisateur::class)
            ->findAll();
        $rates = $entityManager
            ->getRepository(RateHotel::class)
            ->findAll();

        foreach ($hotels as $key => $hotel) {
            if ($hotel->getGouvernorat() != $gouvernorat) {
                unset($hotels[$key]);
            }
        }

        foreach ($plats as $key => $plat) {
            if ($plat->getGouvernorat() != $gouvernorat) {
                unset($plats[$key]);
            }
        }

        foreach ($activities as $key => $activity) {
            if ($activity->getGouvernorat() != $gouvernorat) {
                unset($activities[$key]);
            }
        }

        foreach ($events as $key => $event) {
            if ($event->getGouvernorat() != $gouvernorat) {
                unset($events[$key]);
            }
        }

        foreach ($produits as $key => $produit) {
            if ($produit->getGouvernorat() != $gouvernorat) {
                unset($produits[$key]);
            }
        }

        return $this->render('gouvernorat/show.html.twig', [
            'gouvernorat' => $gouvernorat,
            'plats' => $plats,
            'activities' => $activities,
            'events' => $events,
            'hotels' => $hotels,
            'produits' => $produits,
            'commentaires' => $commentaires,
            'users' => $users,
            'rates' => $rates,
        ]);
    }
}
