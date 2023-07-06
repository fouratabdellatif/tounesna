<?php

namespace App\Controller;

use App\Entity\Activities;
use App\Form\ActivitiesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

#[Route('/admin/activities')]
class ActivitiesAdminController extends AbstractController
{
    #[Route('/', name: 'admin_activities_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $activities = $entityManager
            ->getRepository(Activities::class)
            ->findAll();

        $acts = [];

        foreach ($activities as $activity) {
            $acts[] = [
                'id' => $activity->getIdActivity(),
                'date' => $activity->getDate()->format('Y-m-d H:i:s'),
                'type' => $activity->getType(),
                'event' => $activity->getDescription() . ' | ' . $activity->getAdresse(),
                'start' => $activity->getDate()->format('Y-m-d H:i:s'),
                'end' => $activity->getDate()->format('Y-m-d H:i:s'),
                'title' => $activity->getDescription() . ' | ' . $activity->getAdresse(),
                'description' => $activity->getDescription(),
                'backgroundColor' => $activity->getDescription(),
                'borderColor' => $activity->getDescription(),
                'textColor' => $activity->getDescription(),
                'allDay' => $activity->getDescription(),
            ];
        }

        $data = json_encode($acts);

        return $this->render('activitiesAdmin/index.html.twig', [
            'activities' => $activities,
            'data' => $data
        ]);
    }

    #[Route('/new', name: 'admin_activities_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $activity = new Activities();
        $form = $this->createForm(ActivitiesType::class, $activity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $request->files->get('activities')['image'];
            $uploads_directory = $this->getParameter('uploads_directory');
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($uploads_directory, $filename);
            $activity->setImage($filename);
            $entityManager->persist($activity);
            $entityManager->flush();

            return $this->redirectToRoute('admin_activities_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('activitiesAdmin/new.html.twig', [
            'activity' => $activity,
            'form' => $form,
        ]);
    }

    #[Route('/{idActivity}', name: 'admin_activities_show', methods: ['GET'])]
    public function show(Activities $activity): Response
    {
        return $this->render('activitiesAdmin/show.html.twig', [
            'activity' => $activity,
        ]);
    }

    #[Route('/{idActivity}/edit', name: 'admin_activities_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Activities $activity, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ActivitiesType::class, $activity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $request->files->get('activities')['image'];
            $uploads_directory = $this->getParameter('uploads_directory');
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($uploads_directory, $filename);
            $activity->setImage($filename);
            $entityManager->persist($activity);
            $entityManager->flush();

            return $this->redirectToRoute('admin_activities_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('activitiesAdmin/edit.html.twig', [
            'activity' => $activity,
            'form' => $form,
        ]);
    }

    #[Route('/{idActivity}', name: 'admin_activities_delete', methods: ['POST'])]
    public function delete(Request $request, Activities $activity, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$activity->getIdActivity(), $request->request->get('_token'))) {
            $entityManager->remove($activity);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_activities_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/api/{id}/edit", name="api_activity_edit", methods={"PUT"})
     */
    public function majActivities(?Activities $activity, ManagerRegistry $doctrine, Request $request)
    {
        $donnees = json_decode($request->getContent());

        if (
            isset($donnees->title) && !empty($donnees->title) &&
            isset($donnees->start) && !empty($donnees->start) &&
            isset($donnees->description) && !empty($donnees->description) &&
            isset($donnees->backgroundColor) && !empty($donnees->backgroundColor) &&
            isset($donnees->borderColor) && !empty($donnees->borderColor) &&
            isset($donnees->textColor) && !empty($donnees->textColor)
        ) {
            $code = 200;

            if(!$activity) {
                $activity = new Activities;

                $code = 201;
            }

            $dateTime = new DateTime($donnees->date);
            $dateTimeInterface = DateTimeImmutable::createFromMutable($dateTime);
            $activity->setDate($dateTimeInterface);
            

            $em = $doctrine->getManager();
            $em->persist($activity);
            $em->flush();

            return new Response('OK', $code);
                
        } else {
            return new Response('Données incomplètes', 404);
        }
    }
}
