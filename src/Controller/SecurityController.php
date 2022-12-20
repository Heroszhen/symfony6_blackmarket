<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;

class SecurityController extends AbstractController
{
    #[Route('/connexion', name: 'security_login', methods: ['GET', 'POST'])]
    public function index(AuthenticationUtils $authenticationUtils,Request $request,): Response
    {
        $session = $request->getSession();
        $session->set("page",['general','login']);
        
         // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/index.html.twig', [
            'controller_name' => 'SecurityController',
            'error' => $error,
            "lastusername" => $lastUsername
        ]);
    }

    #[Route('/deconnexion', 'security_logout')]
    public function logout()
    {
        return $this->redirectToRoute('app_home');
    }
}
