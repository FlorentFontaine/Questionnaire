<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $florent = $this->createUser('florent', 'password', User::PROFILE_ADMIN);
        $manager->persist($florent);
        $noel = $this->createUser('noel', 'password', User::PROFILE_ADMIN);
        $manager->persist($noel);
        $Amaury = $this->createUser('amaury', 'password', User::PROFILE_USER);
        $manager->persist($Amaury);
        $manager->flush();
    }

    protected function createUser(string $login, string $password, string $role): User
    {
        $user = new User();
        $user
            ->setLogin($login)
            ->setProfile($role)
            ->setPassword($this->passwordEncoder->encodePassword(
                $user,
                $password
            ));

        return $user;
    }
}
