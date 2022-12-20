<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;

#[Route('/admin')]
class AdminuserController extends AbstractController
{
    #[Route('/tous-les-utilisateurs', name: 'admin_allusers', methods: ['GET'])]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();

        $session = $request->getSession();
        $session->set("page",['admin','allusers']);

        return $this->render('admin/adminuser/index.html.twig', [
            
        ]);
    }
}
