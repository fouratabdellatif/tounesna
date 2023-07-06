<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Entity\ParticipationEvent;
use App\Form\ParticipationEventType;
use App\Repository\ParticipationEventRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;

#[Route('/evenement')]
class EvenementController extends AbstractController
{
    #[Route('/', name: 'events', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $evenements = $entityManager
            ->getRepository(Evenement::class)
            ->findAll();

        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenements,
        ]);
    }

    #[Route('/{idev}', name: 'app_evenement_show', methods: ['GET'])]
    public function show(Evenement $evenement): Response
    {
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    #[Route('/{id}/participer', name: 'app_participation_event_participer', methods: ['GET', 'POST'])]
    public function participerEvent(Request $request, ParticipationEventRepository $participationRepository, UtilisateurRepository $userRepository, Evenement $event): Response
    {
        $participation = new ParticipationEvent();
        $user = $userRepository->find(21);
        $form = $this->createForm(ParticipationEventType::class, $participation);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $now = new DateTime();
            $participation->setDate($now);
            $participation->setUser($user);
            $participation->setEvent($event);
            
            $participationRepository->save($participation, true);
    
            $this->addFlash('notice','Event confirmed!');
    
            return $this->redirectToRoute('events', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('participation_event/new.html.twig', [
            'participation' => $participation,
            'form' => $form,
            'event' => $event
        ]);
    }
}
