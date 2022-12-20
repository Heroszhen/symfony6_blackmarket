<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Service\UtilService;
use App\Service\FileService;
use App\Entity\Product;
use App\Entity\Variant;
use App\Entity\Variantvalue;
use App\Entity\Variantproduct;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/produit')]
class AdminproductController extends AbstractController
{
    #[Route('/tous-les-produits', name: 'admin_allproducts', methods: ['GET'])]
    public function index(Request $request, ManagerRegistry $doctrine, UtilService $us): Response
    {
        $em = $doctrine->getManager();

        $session = $request->getSession();
        $session->set("page",['admin','allproducts']);

        $allproducts = $em->getRepository(Product::class)->findBy([],["id"=>"desc"]);
        $product = new Product();
        $product = $us->serializer($product,"groupadminproduct");
        return $this->render('admin/adminproduct/index.html.twig', [
            "allproducts" => $allproducts,
            "product" => json_decode($product)
        ]);
    }

    #[Route('/switchproductstatus/{id}_{status}', methods: ['GET'])]
    public function switchProductStatus(int $id, int $status, Request $request, ManagerRegistry $doctrine, UtilService $us){
        $response = 0;
        if ($request->isXmlHttpRequest()) {
            $user = $this->getUser();
            if(in_array('ROLE_ADMIN',$user->getRoles())){
                $response = 1;
                $em = $doctrine->getManager();

                $product = $em->find(Product::class,$id);
                $product->setStatus($status);
                $em->persist($product);
                $em->flush();
            }
        }
        return new Response($response);
    }

    #[Route('/editproduct', methods: ['POST'])]
    public function editProduct(Request $request, ManagerRegistry $doctrine, UtilService $us){
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
                    $product = new Product();
                    $product->setCreated(new \DateTime());
                }else{//update
                   $product = $em->find(Product::class,$data["id"]);
                }
                $product
                    ->setTitle($data["title"])
                    ->setDescription($data["description"]);
                if($data['releasedate'] == null || $data['releasedate'] == '')$product->setReleasedate(null);
                else $product->setReleasedate(new \DateTime($data['releasedate']));
                $em->persist($product);
                $em->flush();                    
            }
        }
        //return new Response($response);
        return $this->json($response);
    }


    #[Route('/getproduct/{id}', methods: ['GET'])]
    public function getProduct(int $id, Request $request, ManagerRegistry $doctrine, UtilService $us){
        $response = [
            "status" => 0,
            "data" => null
        ];
        if ($request->isXmlHttpRequest()) {
            $user = $this->getUser();
            if(in_array('ROLE_ADMIN',$user->getRoles())){
                $response['status'] = 1;
                $em = $doctrine->getManager();

                $product = $em->find(Product::class,$id);
                $product = $us->serializer($product,"groupadminproduct");
                $response["data"] = $product;
            }
        }
        return $this->json($response);
    }

    #[Route('/produit_info/{id}', name: 'admin_productinfo', methods: ['GET'])]
    public function productinfo(int $id, Request $request, ManagerRegistry $doctrine, UtilService $us): Response
    {
        $em = $doctrine->getManager();

        $session = $request->getSession();
        $session->set("page",['admin','allproducts']);

        $product = $em->find(Product::class,$id);
        $productM = $us->serializer($product,"groupadminproduct");
        $allvariants = $em->getRepository(Variant::class)->findBy(["product"=>$product]);
        $allvariants = $us->serializer($allvariants,"groupadminproductinfo");
        $allvariantproducts = $em->getRepository(Variantproduct::class)->findBy(["product"=>$product]);dump($allvariantproducts);
        $allvariantproducts = $us->serializer($allvariantproducts,"groupadminproductinfo2");
        $variantproductM = $us->serializer(new Variantproduct(),"groupproductm");
        return $this->render('admin/adminproduct/productinfo.html.twig', [
            "product" => $product,
            "productM" => json_decode($productM),
            "allvariants" => json_decode($allvariants),
            "allvariantproducts" => json_decode($allvariantproducts),
            "variantproductM" => json_decode($variantproductM)
        ]);
    }

    #[Route('/editvariation', methods: ['POST'])]
    public function editVariation( Request $request, ManagerRegistry $doctrine, UtilService $us){
        $response = [
            "status" => 0,
            "data" => null
        ];
        if ($request->isXmlHttpRequest()) {
            $user = $this->getUser();
            if(in_array('ROLE_ADMIN',$user->getRoles())){
                $response['status'] = 1;
                $em = $doctrine->getManager();

                $data = $request->toArray();
                if($data["id"] == null){//add
                    $variant = new Variant();
                    $product = $em->find(Product::class,$data["product"]["id"]);
                    $variant
                        ->setProduct($product)
                        ->setTitle($data["title"])
                        ->setCreated(new \DateTime());
                }else{//update
                    $variant = $em->find(Variant::class,$data["id"]);
                    $variant->setTitle($data["title"]);
                }
                $em->persist($variant);
                $em->flush();
                $variant = $us->serializer($variant,"groupadminproductinfo");
                $response["data"] = json_decode($variant);
            }
        }
        return $this->json($response);
    }

    #[Route('/editvariationvalue', methods: ['POST'])]
    public function editVariationvalue( Request $request, ManagerRegistry $doctrine, UtilService $us){
        $response = [
            "status" => 0,
            "data" => null
        ];
        if ($request->isXmlHttpRequest()) {
            $user = $this->getUser();
            if(in_array('ROLE_ADMIN',$user->getRoles())){
                $response['status'] = 1;
                $em = $doctrine->getManager();

                $data = $request->toArray();
                if($data["id"] == null){//add
                    $variantvalue = new Variantvalue();
                    $variant = $em->find(Variant::class,$data["variant"]["id"]);
                    $variantvalue
                        ->setVariant($variant)
                        ->setTitle($data["title"])
                        ->setCreated(new \DateTime());
                }else{//update
                    $variantvalue = $em->find(Variantvalue::class,$data["id"]);
                    $variantvalue->setTitle($data["title"]);
                }
                $em->persist($variantvalue);
                $em->flush();
                $variantvalue = $us->serializer($variantvalue,"groupadminproductinfo");
                $response["data"] = json_decode($variantvalue);
            }
        }
        return $this->json($response);
    }

    #[Route('/deletevariantproductphoto/{id}_{index}', methods: ['GET'])]
    public function deleteVariantproductPhoto(int $id, int $index, Request $request, ManagerRegistry $doctrine, UtilService $us, FileService $fs){
        $response = [
            "status" => 0,
            "data" => null
        ];
        if ($request->isXmlHttpRequest()) {
            $user = $this->getUser();
            if(in_array('ROLE_ADMIN',$user->getRoles())){
                $response['status'] = 1;
                $em = $doctrine->getManager();

                $vp = $em->find(Variantproduct::class,$id);
                $photos = $vp->getPhotos();
                $photo_name = $photos[$index];
                $newtab = [];
                foreach($photos as $key=>$value){
                    if($key != $index)array_push($newtab,$value);
                }
                $vp->setPhotos($newtab);
                $em->persist($vp);
                $em->flush();
                $fs->deleteOneFile($this->getParameter('upload_dir').$photo_name,$photo_name);
            }
        }
        return $this->json($response);
    }

    #[Route('/editvariantproduct', methods: ['POST'])]
    public function editVariantproduct(Request $request, ManagerRegistry $doctrine, UtilService $us, FileService $fs , SluggerInterface $slugger){
        $response = [
            "status" => 0,
            "data" => null
        ];
        if ($request->isXmlHttpRequest()) {
            $user = $this->getUser();
            if(in_array('ROLE_ADMIN',$user->getRoles())){
                $response['status'] = 1;
                $em = $doctrine->getManager();

                $form = $request->request;
                $files = $request->files;
                if($form->get("id") == "" || $form->get("id") == "null"){
                    $vp = new Variantproduct();
                    $product = $em->find(Product::class,(int)$form->get("productid"));
                    $vp
                        ->setProduct($product)
                        ->setCreated(new \DateTime());
                }else{
                    $vp = $em->find(Variantproduct::class,(int)$form->get('id'));
                }
                $vp->setPrice($form->get("price"));

                //add variantvalues
                if($form->get("values_length") != null){
                    for($i = 0;$i < (int)$form->get("values_length");$i++){
                        $value = $em->find(Variantvalue::class,(int)$form->get("value_{$i}"));
                        $vp->addVariantvalue($value);
                    }
                }
                 
                //add photos
                if($form->get("files_length") != null){
                    $newphotos = $us->getArray($vp->getPhotos());
                    for($i = 0;$i < (int)$form->get("files_length");$i++){
                        $photo = $files->get("file_{$i}");
                        $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                        $safeFilename = $slugger->slug($originalFilename);
                        $newname = $safeFilename.uniqid().".png";
                        try {
                            $photo->move($this->getParameter('upload_dir'), $newname);
                            array_push($newphotos,$newname);
                        } catch (FileException $e) {}
                    }
                    $vp->setPhotos($newphotos);
                }
                $em->persist($vp);
                $em->flush();
                $response["data"] = $us->serializer($vp,"groupadminproductinfo2");
            }
        }
        return $this->json($response);
    }

    #[Route('/switchvariantproductstatus/{id}_{status}', methods: ['GET'])]
    public function switchVariantproductStatus(int $id, int $status, Request $request, ManagerRegistry $doctrine, UtilService $us){
        $response = 0;
        if ($request->isXmlHttpRequest()) {
            $user = $this->getUser();
            if(in_array('ROLE_ADMIN',$user->getRoles())){
                $response = 1;
                $em = $doctrine->getManager();

                $product = $em->find(Variantproduct::class,$id);
                $product->setStatus($status);
                $em->persist($product);
                $em->flush();
            }
        }
        return new Response($response);
    }
}
