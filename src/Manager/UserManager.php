<?php

namespace App\Manager;

use App\DTO\CreateUser;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserManager
{
    private $userRepository;
    private $em;
    private $security;
    private $passwordEncoder;
    private $validator;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $em, Security $security, UserPasswordEncoderInterface $passwordEncoder, ValidatorInterface $validator)
    {
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->security = $security;
        $this->passwordEncoder = $passwordEncoder;
        $this->validator = $validator;
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
            ->setPhone($createUser->phone)
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

    public function updateUser(CreateUser $createUser, User $user) {

        //check if owner
        if ($createUser->id && $this->security->getUser()->getId() != $createUser->id) {
            return new JsonResponse(['errors' => 'Access denied. You can edit only your own account.'], 403);
        }

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


    protected function handleError($violations): array
    {
        $messages = [];
        foreach ($violations as $constraint) {
            $prop = $constraint->getPropertyPath();
            $messages[$prop][] = $constraint->getMessage();
        }
        return $messages;
    }
}
