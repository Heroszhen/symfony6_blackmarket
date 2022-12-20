<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\UserType;
use App\Entity\User;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Service\FileService;

#[Route('/profil')]
class ProfileController extends AbstractController
{
    #[Route('/moi', name: 'profile_me', methods: ['GET','POST'])]
    public function index(Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger, FileService $fs): Response
    {
        $em = $doctrine->getManager();
        $msgalert = "";
        $status = 0;
        $user  = $em->find(User::class,$this->getUser()->getId());
        $form = $this->createForm(UserType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $msgalert = "Erreurs";
            if($form->isValid()){
                $file = $form->get('file')->getData();
                if($file != null){
                    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = $slugger->slug($originalFilename);
                    $newname = $safeFilename.uniqid().".png";
                    try {
                        $file->move($this->getParameter('upload_dir'), $newname);
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }
                    $oldname = $user->getPhoto();
                    if($oldname != '' && file_exists($this->getParameter('upload_dir').$oldname))$fs->deleteOneFile($this->getParameter('upload_dir').$oldname, $oldname);
                    $user->setPhoto($newname);
                }
                $em->persist($user);
                $em->flush();
                $form = $this->createForm(UserType::class,$user);
                $msgalert = "Enregistré";
                $status = 1;
            } 
        }
        return $this->render('profile/index.html.twig', [
            'form' => $form->createView(),
            "msgalert" => $msgalert,
            "status" => $status
        ]);
    }

    /*

    #[Route('/modifymyprofile', methods: ['GET','POST'])]
    public function modifyMyProfile(Request $request, ManagerRegistry $doctrine){
        if ($request->isXmlHttpRequest()) {
            $em = $doctrine->getManager();
            $msgalert = "";
            $user  = $em->find(User::class,$this->getUser()->getId());
            $form = $this->createForm(UserType::class,$user);
            $form->handleRequest($request);
            if($form->isSubmitted()){
                if($form->isValid()){
                    $file = $form->get('file')->getData();
                    // $em->persist($user);
                    // $em->flush();
                    $form = $this->createForm(UserType::class,$user);
                    $msgalert = $file->guessExtension();
                }
            }
            return $this->render('profile/userform.html.twig', [
                'form' => $form->createView(),
                "msgalert" => $msgalert
            ]);
        }
        return new Response(0);
    }*/

}
