<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const ADMIN_USER = 'user_admin';
    public const DEMO_USER = 'user_demo';

    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $users = [
            // référence => [email, pseudo, rôles]
            self::ADMIN_USER => ['admin@overload.test', 'Admin', ['ROLE_ADMIN']],
            self::DEMO_USER  => ['demo@overload.test', 'Demo', []],
        ];

        foreach ($users as $ref => [$email, $pseudo, $roles]) {
            $user = (new User())
                ->setEmail($email)
                ->setPseudo($pseudo)
                ->setRoles($roles)
                ->setCreatedAt(new \DateTimeImmutable('-3 months'));

            // mot de passe "password" pour tous les comptes de test
            $user->setPassword($this->hasher->hashPassword($user, 'password'));

            $manager->persist($user);
            $this->addReference($ref, $user);
        }

        $manager->flush();
    }
}