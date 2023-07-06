<?php

namespace App\Controller;

use App\Entity\Hotel;
use App\Entity\Commentaire;
use App\Entity\RateHotel;
use App\Entity\Utilisateur;
use App\Entity\Reservation;
use App\Form\CommentaireType;
use App\Form\HotelType;
use App\Form\RateHotelType;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;
use App\Service\TwilioSmsService;

#[Route('/hotel')]
class HotelController extends AbstractController
{
    private $twilioSmsService;

    public function __construct(TwilioSmsService $twilioSmsService)
    {
        $this->twilioSmsService = $twilioSmsService;
    }

    #[Route('/', name: 'hotels', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager
            ->getRepository(Utilisateur::class)
            ->findAll();
        $hotels = $entityManager
            ->getRepository(Hotel::class)
            ->findAll();
        $commentaires = $entityManager
            ->getRepository(Commentaire::class)
            ->findAll();
        $rates = $entityManager
            ->getRepository(RateHotel::class)
            ->findAll();

        return $this->render('hotel/index.html.twig', [
            'hotels' => $hotels,
            'commentaires' => $commentaires,
            'rates' => $rates,
            'users' => $users,
        ]);
    }

    #[Route('/{idh}', name: 'app_hotel_show', methods: ['GET'])]
    public function show(Hotel $hotel): Response
    {
        return $this->render('hotel/show.html.twig', [
            'hotel' => $hotel,
        ]);
    }

    #[Route('/{id}/comment/add', name: 'app_hotel_comment', methods: ['GET', 'POST'])]
    
    public function addComment(Request $request, Hotel $hotel, EntityManagerInterface $entityManager)
{
    $now = new DateTime();
    $user = $entityManager->find(Utilisateur::class, 21);
    $comment = new Commentaire();
    $comment->setIdHotel($hotel->getIdh());
    $comment->setDateajc($now);
    $comment->setAuteur($user->getIduser());
    $form = $this->createForm(CommentaireType::class, $comment);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($comment);
        $entityManager->flush();

        $this->addFlash('notice','Commentaire ajouté!');

        return $this->redirectToRoute('hotels', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('hotel/comment.html.twig', [
        'form' => $form->createView(),
    ]);
}

#[Route('/{id}/comment/edit', name: 'app_hotel_comment_edit', methods: ['GET', 'POST'])]
    
public function editComment(Request $request, Commentaire $comment, EntityManagerInterface $entityManager)
{
$now = new DateTime();
// $user = $entityManager->find(Utilisateur::class, 21);
// $comment = new Commentaire();
// $comment->setIdHotel($hotel->getIdh());
$comment->setDateajc($now);
// $comment->setAuteur($user->getIduser());
$form = $this->createForm(CommentaireType::class, $comment);
$form->handleRequest($request);

if ($form->isSubmitted() && $form->isValid()) {
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($comment);
    $entityManager->flush();

    $this->addFlash('notice','Commentaire updated!');

    return $this->redirectToRoute('hotels', [], Response::HTTP_SEE_OTHER);
}

return $this->render('hotel/comment.html.twig', [
    'form' => $form->createView(),
]);
}

#[Route('/{id}/rate/add', name: 'app_hotel_rate', methods: ['GET', 'POST'])]
    
public function addRate(Request $request, Hotel $hotel, EntityManagerInterface $entityManager)
{
$user = $entityManager->find(Utilisateur::class, 21);
$rate = new RateHotel();
$rate->setIdHotel($hotel);
$rate->setIdUser($user->getIduser());
$form = $this->createForm(RateHotelType::class, $rate);
$form->handleRequest($request);

if ($form->isSubmitted() && $form->isValid()) {
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($rate);
    $entityManager->flush();

    $this->addFlash('notice','Rating ajouté!');

    return $this->redirectToRoute('hotels', [], Response::HTTP_SEE_OTHER);
}

return $this->render('hotel/rate.html.twig', [
    'form' => $form->createView(),
]);
}


#[Route('/{id}/reserver', name: 'app_reservation_hotel', methods: ['GET', 'POST'])]
public function reserverHotel(Request $request, ReservationRepository $reservationRepository, UtilisateurRepository $userRepository, Hotel $hotel): Response
{
    $reservation = new Reservation();
    $user = $userRepository->find(21);
    $form = $this->createForm(ReservationType::class, $reservation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $now = new DateTime();
        $reservation->setDate($now);
        $reservation->setUser($user);
        $reservation->setHotel($hotel);
        
        $reservationRepository->save($reservation, true);

        $message =
            'Hello ' .
            $reservation->getUser()->getNom() .
            ' ' .
            $reservation->getUser()->getPrenom() .
            ' | ' .
            $reservation->getUser()->getEmail() .
            ', your reservation for ' .
            $reservation->getHotel()->getNomhotel() .
            ' Hotel, in ' .
            $reservation->getHotel()->getGouvernorat()->getNomGouver() .
            ' for a number of places of ' .
            $reservation->getNbPlaces() .
            ' and a price of ' .
            $reservation->getNbPlaces() * $reservation->getHotel()->getPrice() .
            'TND, has been successfully confirmed with the date ' .
            $reservation->getDate()->format('d-m-Y') .
            '. You can visit the hotel website for more info: ' .
            $reservation->getHotel()->getSite() .
            '. Thank you for your trust.';
        $phoneNumber = '+21653353908'; // user's phone number

        $this->twilioSmsService->sendSms($message, $phoneNumber);

        $this->addFlash('notice','Hotel réservé!');

        return $this->redirectToRoute('hotels', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('reservation/new.html.twig', [
        'reservation' => $reservation,
        'form' => $form,
        'hotel' => $hotel
    ]);
}

}
