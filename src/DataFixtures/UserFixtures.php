<?php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Service\FileService;

class UserFixtures extends Fixture
{
    private $passwordHasher;
    private $fs;

    public function __construct(UserPasswordHasherInterface $passwordHasher, FileService $fs)
    {
        $this->passwordHasher = $passwordHasher;
        $this->fs = $fs;
    }

    public function load(ObjectManager $manager):void {
        $this->fs->deleteAllFiles();
        //create one administrator
        $user = new User();
        $plainpassword = "aaaaaaaa";
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $plainpassword
        );
        $user->setPassword($hashedPassword);
        $user
            ->setLastname("Heros")
            ->setFirstname("zhen")
            ->setEmail("zhen@gmail.com")
            ->setRoles(["ROLE_ADMIN","ROLE_USER"])
            ->setPhoto("fixtures1.png")
            ->setCreated(new \DateTime())
        ;
        $manager->persist($user);
        $manager->flush();

        //create one user
        $user = new User();
        $plainpassword = "aaaaaaaa";
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $plainpassword
        );
        $user->setPassword($hashedPassword);
        $user
            ->setLastname("Federer")
            ->setFirstname("Roger")
            ->setEmail("roger@gmail.com")
            ->setRoles(["ROLE_USER"])
            ->setPhoto("fixtures1.png")
            ->setCreated(new \DateTime())
        ;
        $manager->persist($user);
        $manager->flush();
    }
}