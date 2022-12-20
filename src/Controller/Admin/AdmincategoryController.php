<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Category;
use App\Service\UtilService;

#[Route('/admin/categorie')]
class AdmincategoryController extends AbstractController
{
    #[Route('/toutes-les-categories', name: 'admin_allcategorys', methods: ['GET'])]
    public function index(Request $request, ManagerRegistry $doctrine, UtilService $us): Response
    {
        $em = $doctrine->getManager();

        $session = $request->getSession();
        $session->set("page",['admin','allcategorys']);

        $allcategorys = $em->getRepository(category::class)->findAll();
        $allcategorys = $us->serializer($allcategorys,"groupadmincategory");
        return $this->render('admin/admincategory/index.html.twig', [
            "allcategorys" => json_decode($allcategorys)
        ]);
    }

    #[Route('/edit-category', methods: ['POST'])]
    public function editCategory(Request $request, ManagerRegistry $doctrine, UtilService $us){
        $response = [
            "status" => 0,
            "data" => null
        ];
        if ($request->isXmlHttpRequest()) {
            $user = $this->getUser();
            if(in_array('ROLE_ADMIN',$user->getRoles())){
                $response["status"] = 1;
                $em = $doctrine->getManager();

                $data = $request->toArray();
                if($data["id"] == null){//add
                    $category = new Category();
                    $category->setCreated(new \DateTime());
                }else{//update
                    $category = $em->find(Category::class,$data["id"]);
                }
                $category
                        ->setTitle($data["title"])
                        ->setStatus($data["status"]);
                $em->persist($category);
                $em->flush();
                $response["data"] = $us->serializer($category,"groupadmincategory");
            }
        }
        //return new Response($response);
        return $this->json($response);
    }

    #[Route('/edit-categorystatus/{id}_{status}', methods: ['GET'])]
    public function editCategoryStatus(int $id, int $status, Request $request, ManagerRegistry $doctrine, UtilService $us){
        $response = 0;
        if ($request->isXmlHttpRequest()) {
            $user = $this->getUser();
            if(in_array('ROLE_ADMIN',$user->getRoles())){
                $response = 1;
                $em = $doctrine->getManager();

                $category = $em->find(Category::class,$id);
                $category->setStatus($status);
                $em->persist($category);
                $em->flush();
            }
        }
        return new Response($response);
    }

    #[Route('/deletecategory/{id}', methods: ['GET'])]
    public function deleteCategory(int $id, Request $request, ManagerRegistry $doctrine, UtilService $us){
        $response = 0;
        if ($request->isXmlHttpRequest()) {
            $user = $this->getUser();
            if(in_array('ROLE_ADMIN',$user->getRoles())){
                $response = 1;
                $em = $doctrine->getManager();

                $category = $em->find(Category::class,$id);
                if($category->getProducts()->count() != 0)$response = 2;
                else{
                    $em->remove($category);
                    $em->flush();
                }
            }
        }
        return new Response($response);
    }
}
