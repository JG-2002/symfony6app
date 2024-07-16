<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordHasherInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {

        foreach ($this->getUserData() as [$password, $email,  $role]) {
            $user = new User();
            $user->setPassword($this->passwordEncoder->hashPassword($user, $password));
            $user->setEmail($email);
            $user->setRoles($role);
            $manager->persist($user);
        }
        $manager->flush();
    }

    private function getUserData(): array
    {
        return [
            ['admin31', 'junior@gdt-core.com', ['ROLE_ADMIN']],
        ];
    }
}
