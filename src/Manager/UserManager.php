<?php

namespace App\Manager;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserManager
{
    private $userRepository;
    private $em;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->userRepository = $userRepository;
    }

    public function create(User $user): User
    {
        $newUser = new User();
        $newUser->setFirstName($user->getFirstName());
        $newUser->setLastName($user->getLastName());
        $newUser->setEmail($user->getEmail());
        $newUser->setPassword($user->getPassword());
        $this->em->persist($newUser);
        $this->em->flush();

        return $newUser;
    }

    public function find($id): ?User
    {
        return $this->userRepository->find($id);
    }

    public function all(): array
    {
        return $this->userRepository->findAll();
    }

    public function delete($id): array
    {
        $user = $this->find($id);
        $handledData = [];
        if ($user) {
            $handledData = ['email' => $user->getEmail(), 'firstName' => $user->getFirstName(), 'lastName' => $user->getLastName()];
        }

        //TODO: delete method
        //var_dump($handledData); exit;
        //$this->em->remove($user);
        //$this->em->flush();
        return $handledData;
    }


    public function update(User $user, $flush = false)
    {
        $this->em->persist($user);
        if ($flush) {
            $this->em->flush();
        }
    }

}
