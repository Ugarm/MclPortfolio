<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminUserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Create a new User instance (admin user)
        $user = new Users();
        $user->setEmail('m-cl@outlook.com');
        $user->setRoles(['ROLE_SUPER_ADMIN']);

        // Hash the password
        $hashedPassword = $this->passwordHasher->hashPassword($user, 'root');
        $user->setPassword($hashedPassword);

        // Persist the user

        $manager->persist($user);

        // Flush to save it to the database
        $manager->flush();
    }
}
