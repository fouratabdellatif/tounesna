<?php

namespace App\Controller;

use App\Entity\Activities;
use App\Entity\ParticipationActivity;
use App\Form\ActivitiesType;
use App\Form\ParticipationActivityType;
use App\Repository\ParticipationActivityRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;

#[Route('/activities')]
class ActivitiesController extends AbstractController
{
    #[Route('/', name: 'activities', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $activities = $entityManager
            ->getRepository(Activities::class)
            ->findAll();

        return $this->render('activities/index.html.twig', [
            'activities' => $activities,
        ]);
    }

    #[Route('/{idActivity}', name: 'app_activities_show', methods: ['GET'])]
    public function show(Activities $activity): Response
    {
        return $this->render('activities/show.html.twig', [
            'activity' => $activity,
        ]);
    }


    #[Route('/{id}/participer', name: 'app_participation_activity_participer', methods: ['GET', 'POST'])]
    public function participerActivity(Request $request, ParticipationActivityRepository $participationRepository, UtilisateurRepository $userRepository, Activities $activity): Response
    {
        $participation = new ParticipationActivity();
        $user = $userRepository->find(21);
        $form = $this->createForm(ParticipationActivityType::class, $participation);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $now = new DateTime();
            $participation->setDate($now);
            $participation->setUser($user);
            $participation->setActivity($activity);
            
            $participationRepository->save($participation, true);
    
            $this->addFlash('notice','Activity confirmed!');
    
            return $this->redirectToRoute('activities', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('participation_activity/new.html.twig', [
            'participation' => $participation,
            'form' => $form,
            'activity' => $activity
        ]);
    }
}
