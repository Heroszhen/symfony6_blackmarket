<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Variantproduct;
use Doctrine\Persistence\ManagerRegistry;
use App\Service\UtilService;

//#[Route('%prefix%/aaa')]
class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
dump($_ENV['URL']);
        $session = $request->getSession();
        $session->set("page",['general','home']);

        $allcategorys = $em->getRepository(Category::class)->findBy(["status"=>1]);
        return $this->render('home/index.html.twig', [
            "allcategorys" => $allcategorys
        ]);
    }

    #[Route('/categorie/{uuid}', name: 'app_category')]
    public function getProductsByCategory(String $uuid, Request $request, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();

        $session = $request->getSession();
        $session->set("page",['general','category']);

        $category = $em->getRepository(Category::class)->findOneBy(['uuid'=>$uuid]);
        return $this->render('home/category.html.twig', [
            "category" => $category
        ]);
    }

    #[Route('/searchbykeywords', name: 'app_searchbykeywords', methods: ['POST'])]
    public function searchByKeywords(Request $request){
        return $this->redirectToRoute('app_search', ['q', $request->request->get('keywords')]);
    }
    #[Route('/rechercher', name: 'app_search', methods: ['GET'])]
    public function search(Request $request, ManagerRegistry $doctrine){
        $em = $doctrine->getManager();
        $session = $request->getSession();
        $session->set("page",['general','search']);

        $allcategorys = $em->getRepository(Category::class)->findBy([],['title'=>"asc"]);
        $keywords = $request->query->get("q");
        $store = [];
        $ids = [];
        $categorys = $request->query->get("categorys");
        $categorys2 = [];
        if($categorys != null && $categorys != ""){
            $tab = explode("_",$categorys);
            foreach($tab as $title){
                foreach($allcategorys as $key=>$item){
                    if($item->getTitle() == $title){
                        array_push($store,[$key,$title]);
                        array_push($ids,$item->getId());
                        array_push($categorys2 ,$item);
                        break;
                    }
                }
            }
        }
        $found = $em->getRepository(Product::class)->findByKeywords($keywords, $categorys2);
        return $this->render('home/search.html.twig', [
            "keywords" => $keywords,
            "allcategorys" => $allcategorys,
            "store" => $store,
            "ids" => $ids,
            "allproducts" => $found
        ]);
    }

    #[Route('/produit/{uuid}', name: 'app_product')]
    public function getProduct(String $uuid, Request $request, ManagerRegistry $doctrine, UtilService $us): Response
    {
        $em = $doctrine->getManager();

        $session = $request->getSession();
        $session->set("page",['general','product']);

        $product = $em->getRepository(Product::class)->findOneBy(['uuid'=>$uuid]);
        $allvproducts = $em->getRepository(Variantproduct::class)->findBy(["product"=>$product]);
        $allvproducts = $us->serializer($allvproducts,"groupvproduct");
        $product = $us->serializer($product,"groupproduct");
        
        return $this->render('home/product.html.twig', [
            "product" => json_decode($product),
            "allvproducts" => json_decode($allvproducts)
        ]);
    }

}
