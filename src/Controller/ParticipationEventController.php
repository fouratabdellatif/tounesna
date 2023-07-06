<?php

namespace App\Controller;

use App\Entity\ParticipationEvent;
use App\Form\ParticipationEventType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/participation/event')]
class ParticipationEventController extends AbstractController
{
    #[Route('/', name: 'app_participation_event_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $participationEvents = $entityManager
            ->getRepository(ParticipationEvent::class)
            ->findAll();

        return $this->render('participation_event/index.html.twig', [
            'participation_events' => $participationEvents,
        ]);
    }

    #[Route('/new', name: 'app_participation_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $participationEvent = new ParticipationEvent();
        $form = $this->createForm(ParticipationEventType::class, $participationEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($participationEvent);
            $entityManager->flush();

            return $this->redirectToRoute('app_participation_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('participation_event/new.html.twig', [
            'participation_event' => $participationEvent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_participation_event_show', methods: ['GET'])]
    public function show(ParticipationEvent $participationEvent): Response
    {
        return $this->render('participation_event/show.html.twig', [
            'participation_event' => $participationEvent,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_participation_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ParticipationEvent $participationEvent, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ParticipationEventType::class, $participationEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_participation_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('participation_event/edit.html.twig', [
            'participation_event' => $participationEvent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_participation_event_delete', methods: ['POST'])]
    public function delete(Request $request, ParticipationEvent $participationEvent, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$participationEvent->getId(), $request->request->get('_token'))) {
            $entityManager->remove($participationEvent);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_participation_event_index', [], Response::HTTP_SEE_OTHER);
    }
}
