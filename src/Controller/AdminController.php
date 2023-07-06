<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'admin')]
    public function admin(): Response
    {
        return $this->render('back/admin.html.twig', [
            'controller_name' => 'admin',
        ]);
    }
    
    #[Route('/login', name: 'login_admin')]
    public function login(): Response
    {
        return $this->render('back/login.html.twig', [
            'controller_name' => 'login',
        ]);
    }
    
    #[Route('/register', name: 'register_admin')]
    public function regsiter(): Response
    {
        return $this->render('back/register.html.twig', [
            'controller_name' => 'register',
        ]);
    }
}
