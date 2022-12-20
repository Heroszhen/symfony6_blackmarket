<?php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Category;
use Faker\Factory;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager):void {
        $faker = Factory::create();
        for($i = 0; $i < 10; $i++){
            $category = new Category();
            $category
                ->setTitle($faker->word())
                ->setCreated(new \DateTime())
            ;
            $manager->persist($category);
        }
        $manager->flush();
    }
}