<?php

namespace App\Manager;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserManager
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    private $formFactory;
    private $requestStack;
    private $em;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->userRepository = $userRepository;

    }


    public function create(User $user)
    {
        $newUser = new User();
        $newUser->setFirstName($user->getFirstName());
        $newUser->setLastName($user->getLastName());
        $newUser->setEmail($user->getEmail());
        $newUser->setPassword($user->getPassword());


        return $newUser;
    }

    public function find($id)
    {
        return $this->userRepository->find($id);
    }

    public function all()
    {
        return $this->userRepository->findAll();
    }

    public function delete($id)
    {
        $user = $this->find($id);
        $handledData = ['email' => $user->getEmail(), 'firstName' => $user->getFirstName(), 'lastName' => $user->getLastName()];
        $this->em->remove($user);
        $this->em->flush();
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
