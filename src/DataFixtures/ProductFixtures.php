<?php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Variant;
use App\Entity\Variantvalue;
use App\Entity\Variantproduct;
use App\Entity\Comment;
use App\Entity\User;
use Faker\Factory;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager):void {
        $faker = Factory::create();

        for($i = 0; $i < 50; $i++){
            set_time_limit(0);
            $n = rand(1, 10);
            $category = $manager->find(Category::class,$n);
            $product = new Product();
            $product
                ->addCategory($category)
                ->setStatus(1)
                ->setTitle($faker->name())
                ->setDescription($faker->realText(rand(1000, 1500)))
                ->setReleasedate($faker->dateTimeBetween('-10 month', '0 month'))
                ->setCreated(new \DateTime());
            ;

            $manager->persist($product);
            $manager->flush();

            //variant 1
            $v1 = new Variant();
            $v1
                ->setTitle("Condition")
                ->setProduct($product);
            $manager->persist($v1);
            $manager->flush();
            $value1_1 = new Variantvalue();
            $value1_1
                ->setTitle("Etat correct")
                ->setVariant($v1)
            ;
            $manager->persist($value1_1);
            $value1_2 = new Variantvalue();
            $value1_2
                ->setTitle("Très bon état")
                ->setVariant($v1)
            ;
            $manager->persist($value1_2);
            $manager->flush();

            //variant 2
            $v2 = new Variant();
            $v2
                ->setTitle("Capacité")
                ->setProduct($product);
            $manager->persist($v2);
            $manager->flush();
            $value2_1 = new Variantvalue();
            $value2_1
                ->setTitle("256 GO")
                ->setVariant($v2)
            ;
            $manager->persist($value2_1);
            $value2_2 = new Variantvalue();
            $value2_2
                ->setTitle("512 GO")
                ->setVariant($v2)
            ;
            $manager->persist($value2_2);
            $manager->flush();

            //variant product
            $vproduct = new Variantproduct();
            $vproduct
                ->setProduct($product)
                ->setPhotos(["fixtures2.png","fixtures3.png","fixtures4.png","fixtures5.png"])
                ->setPrice(rand(10,100));
            $vproduct->addVariantvalue($value1_1);
            $vproduct->addVariantvalue($value2_1);
            $manager->persist($vproduct);
            //$manager->flush();

            $vproduct = new Variantproduct();
            $vproduct
                ->setProduct($product)
                ->setPhotos(["fixtures2.png","fixtures3.png","fixtures4.png","fixtures5.png"])
                ->setPrice(rand(10,100));
            $vproduct->addVariantvalue($value1_1);
            $vproduct->addVariantvalue($value2_2);
            $manager->persist($vproduct);
            
            $vproduct = new Variantproduct();
            $vproduct
                ->setProduct($product)
                ->setPhotos(["fixtures2.png","fixtures3.png","fixtures4.png","fixtures5.png"])
                ->setPrice(rand(10,100));
            $vproduct->addVariantvalue($value1_2);
            $vproduct->addVariantvalue($value2_1);
            $manager->persist($vproduct);

            $vproduct = new Variantproduct();
            $vproduct
                ->setProduct($product)
                ->setPhotos(["fixtures2.png","fixtures3.png","fixtures4.png","fixtures5.png"])
                ->setPrice(rand(10,100));
            $vproduct->addVariantvalue($value1_2);
            $vproduct->addVariantvalue($value2_2);
            $manager->persist($vproduct);
            
            $manager->flush();

            //comments
            $user = $manager->find(User::class,1);
            for($j = 0;$j < 10;$j++){
                $comment = new Comment;
                $comment
                    ->setUser($user)
                    ->setProduct($product)
                    ->setMessage($faker->realText(rand(100, 200)))
                    ->setStars(rand(0,5))
                    ->setCreated(new \DateTime())
                ;
                $manager->persist($comment);
            }
            $manager->flush();
        }
       
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            CategoryFixtures::class,
        );
    }
}