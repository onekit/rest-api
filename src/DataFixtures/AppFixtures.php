<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class AppFixtures extends Fixture implements OrderedFixtureInterface
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user
            ->setFirstName($_ENV['ADMIN_FIRST_NAME'])
            ->setLastName($_ENV['ADMIN_LAST_NAME'])
            ->setPhone($_ENV['ADMIN_PHONE_NUMBER'])
            ->setEmail($_ENV['ADMIN_EMAIL'])
            ->setRoles(['ROLE_USER', 'ROLE_ADMIN']);

        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            $_ENV['ADMIN_PASSWORD']
        ));

        $manager->persist($user);
        $manager->flush();
    }

    public function getOrder(): int
    {
        return 1;
    }
}