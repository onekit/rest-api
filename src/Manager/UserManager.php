<?php

namespace App\Manager;

use App\DTO\CreateUser;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserManager extends ApiManager
{
    private $userRepository;
    private $em;
    private $passwordEncoder;
    private $validator;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder, ValidatorInterface $validator)
    {
        $this->userRepository = $userRepository;
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
        $this->validator = $validator;
    }

    /**
     * @param User $user
     * @param CreateUser $createUser
     * @return User
     */
    public function assignUser(User $user, CreateUser $createUser): User
    {
        if ($createUser->firstName) {
            $user->setFirstName($createUser->firstName);
        }

        if ($createUser->lastName) {
            $user->setLastName($createUser->lastName);
        }

        if ($createUser->phone) {
            $user->setPhone($createUser->phone);
        }

        if ($createUser->email) {
            $user->setEmail($createUser->email);
        }
        if ($createUser->password) {
            $user->setPassword($this->passwordEncoder->encodePassword($user, $createUser->password));
        }

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

    public function updateUser(CreateUser $createUser, User $user = null)
    {
        $user = $user ? $user : new User();
        $this->assignUser($user, $createUser);
        $constraints = $this->validator->validate($user);
        if ($constraints->count()) {
            return new JsonResponse(['errors' => $this->handleError($constraints)], 400);
        }
        return $this->update($user, true);
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
