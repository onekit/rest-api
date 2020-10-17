<?php

namespace App\Manager;

use App\Entity\Input\CreateUser;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class UserManager
{
    private $userRepository;
    private $em;
    private $passwordEncoder;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param User $user
     * @param CreateUser $createUser
     * @return User|JsonResponse
     */
    public function assignUser(User $user, CreateUser $createUser)
    {
        $user->setFirstName($createUser->firstName)
            ->setLastName($createUser->lastName)
            ->setEmail($createUser->email)
            ->setPassword($this->passwordEncoder->encodePassword($user, $createUser->password));

        return $user;
    }

    public function find($id): ?User
    {
        return $this->userRepository->find($id);
    }

    public function all(): array
    {
        return $this->userRepository->findAll();
    }

    public function delete($id): JsonResponse
    {
        $user = $this->find($id);
        if (!$user) {
            return new JsonResponse('User not found', 404);
        }
        $this->em->remove($user);
        $this->em->flush();

        return new JsonResponse(null, 204);
    }

    public function update(User $user, $flush = false): User
    {
        $this->em->persist($user);
        if ($flush) {
            $this->em->flush();
        }
        return $user;
    }
}
