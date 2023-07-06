<?php

namespace App\Controller;

use App\Entity\ParticipationActivity;
use App\Form\ParticipationActivityType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/participation/activity')]
class ParticipationActivityController extends AbstractController
{
    #[Route('/', name: 'app_participation_activity_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $participationActivities = $entityManager
            ->getRepository(ParticipationActivity::class)
            ->findAll();

        return $this->render('participation_activity/index.html.twig', [
            'participation_activities' => $participationActivities,
        ]);
    }

    #[Route('/new', name: 'app_participation_activity_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $participationActivity = new ParticipationActivity();
        $form = $this->createForm(ParticipationActivityType::class, $participationActivity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($participationActivity);
            $entityManager->flush();

            return $this->redirectToRoute('app_participation_activity_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('participation_activity/new.html.twig', [
            'participation_activity' => $participationActivity,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_participation_activity_show', methods: ['GET'])]
    public function show(ParticipationActivity $participationActivity): Response
    {
        return $this->render('participation_activity/show.html.twig', [
            'participation_activity' => $participationActivity,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_participation_activity_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ParticipationActivity $participationActivity, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ParticipationActivityType::class, $participationActivity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('notice','Nombre de places updated!');

            return $this->redirectToRoute('app_participation_activity_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('participation_activity/edit.html.twig', [
            'participation_activity' => $participationActivity,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_participation_activity_delete', methods: ['POST'])]
    public function delete(Request $request, ParticipationActivity $participationActivity, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$participationActivity->getId(), $request->request->get('_token'))) {
            $entityManager->remove($participationActivity);
            $entityManager->flush();

            $this->addFlash('notice','Participation deleted!');
        }

        return $this->redirectToRoute('app_participation_activity_index', [], Response::HTTP_SEE_OTHER);
    }
}
