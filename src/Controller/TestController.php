<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Service\FileService;

class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(UserPasswordHasherInterface $passwordHasher,ManagerRegistry $doctrine, FileService $fs): Response
    {
       
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
