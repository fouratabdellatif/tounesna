<?php

namespace App\Controller;

use App\Entity\Plat;
use App\Entity\Gouvernorat;
use App\Form\PlatType;
use App\Repository\PlatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;

#[Route('/admin/plat')]
class PlatAdminController extends AbstractController
{
    #[Route('/search', name: 'plat_search')]
    public function search(EntityManagerInterface $entityManager, Request $request, PlatRepository $platRepository): Response
    {
        $plats = $entityManager
            ->getRepository(Plat::class)
            ->findAll();
        $gouvernorats = $entityManager
            ->getRepository(Gouvernorat::class)
            ->findAll();
            
        $pieChart = new PieChart();

        $charts = [['Plat', 'Number per Gouvernorat']];

        foreach ($gouvernorats as $g) {
            $gouvernoratN = 0;
            foreach ($plats as $p) {
                if ($g == $p->getGouvernorat()) {
                    $gouvernoratN++;
                }
            }

            array_push($charts, [$g->getNomGouver(), $gouvernoratN]);
        }
        
        $pieChart->getData()->setArrayToDataTable($charts);

        // dd($pieChart);

        $pieChart->getOptions()->setTitle('Plats Number per Gouvernorats');
        $pieChart->getOptions()->setHeight(400);
        $pieChart->getOptions()->setWidth(400);
        $pieChart
            ->getOptions()
            ->getTitleTextStyle()
            ->setColor('#07600');
        $pieChart
            ->getOptions()
            ->getTitleTextStyle()
            ->setFontSize(25);
        $query = $request->query->get('q');
        $plats = $platRepository->findByNom($query);

        return $this->render('platAdmin/search.html.twig', [
            'plats' => $plats,
            'query' => $query,
            'piechart' => $pieChart,
        ]);
    }
    
    #[Route('/', name: 'admin_plat_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, Request $request, PlatRepository $platRepository): Response
    {
        $plats = $entityManager
            ->getRepository(Plat::class)
            ->findAll();
        $gouvernorats = $entityManager
            ->getRepository(Gouvernorat::class)
            ->findAll();
            
        $pieChart = new PieChart();

        $charts = [['Plat', 'Number per Gouvernorat']];

        foreach ($gouvernorats as $g) {
            $gouvernoratN = 0;
            foreach ($plats as $p) {
                if ($g == $p->getGouvernorat()) {
                    $gouvernoratN++;
                }
            }

            array_push($charts, [$g->getNomGouver(), $gouvernoratN]);
        }
        
        $pieChart->getData()->setArrayToDataTable($charts);

        // dd($pieChart);

        $pieChart->getOptions()->setTitle('Plats Number per Gouvernorats');
        $pieChart->getOptions()->setHeight(400);
        $pieChart->getOptions()->setWidth(400);
        $pieChart
            ->getOptions()
            ->getTitleTextStyle()
            ->setColor('#07600');
        $pieChart
            ->getOptions()
            ->getTitleTextStyle()
            ->setFontSize(25);
        $query = $request->query->get('q');
        $plats = $platRepository->findByNom($query);

        return $this->render('platAdmin/index.html.twig', [
            'plats' => $plats,
            'piechart' => $pieChart,
            'query' => $query,
        ]);
    }

    #[Route('/new', name: 'admin_plat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $plat = new Plat();
        $form = $this->createForm(PlatType::class, $plat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $request->files->get('plat')['image'];
            $uploads_directory = $this->getParameter('uploads_directory');
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($uploads_directory, $filename);
            $plat->setImage($filename);
            $entityManager->persist($plat);
            $entityManager->flush();

            return $this->redirectToRoute('admin_plat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('platAdmin/new.html.twig', [
            'plat' => $plat,
            'form' => $form,
        ]);
    }

    #[Route('/{idplat}', name: 'admin_plat_show', methods: ['GET'])]
    public function show(Plat $plat): Response
    {
        return $this->render('platAdmin/show.html.twig', [
            'plat' => $plat,
        ]);
    }

    #[Route('/{idplat}/edit', name: 'admin_plat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Plat $plat, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PlatType::class, $plat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $request->files->get('plat')['image'];
            $uploads_directory = $this->getParameter('uploads_directory');
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($uploads_directory, $filename);
            $plat->setImage($filename);
            $entityManager->persist($plat);
            $entityManager->flush();

            return $this->redirectToRoute('admin_plat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('platAdmin/edit.html.twig', [
            'plat' => $plat,
            'form' => $form,
        ]);
    }

    #[Route('/{idplat}', name: 'admin_plat_delete', methods: ['POST'])]
    public function delete(Request $request, Plat $plat, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$plat->getIdplat(), $request->request->get('_token'))) {
            $entityManager->remove($plat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_plat_index', [], Response::HTTP_SEE_OTHER);
    }
}
