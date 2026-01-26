<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setEmail('admin@collection.com');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $user1 = new User();
        $user1->setUsername('demo');
        $user1->setEmail('demo@rubikscube.com');
        $user1->setPassword($this->passwordHasher->hashPassword($user1, 'demo123'));
        $manager->persist($user1);

        $user2 = new User();
        $user2->setUsername('collector');
        $user2->setEmail('collector@rubikscube.com');
        $user2->setPassword($this->passwordHasher->hashPassword($user2, 'collector123'));
        $manager->persist($user2);

        $manager->flush();
    }
}
