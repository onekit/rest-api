<?php

namespace App\Manager;

use App\Entity\Input\CreateUser;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use JsonException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager
{
    private $userRepository;
    private $em;
    private $passwordEncoder;
    private $formFactory;
    private $requestStack;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder, FormFactoryInterface $formFactory, RequestStack $requestStack)
    {
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->formFactory = $formFactory;
        $this->requestStack = $requestStack;
    }

    /**
     * @param CreateUser $createUser
     * @return User|JsonResponse
     */
    public function createUser(CreateUser $createUser)
    {
        $user = new User();
        $user->setFirstName($createUser->firstName)
            ->setLastName($createUser->lastName)
            ->setEmail($createUser->email)
            ->setPassword($this->passwordEncoder->encodePassword($user, $createUser->password));

        return $this->update($user, true);
    }

    public function updateUser(User $user, CreateUser $createUser): User
    {
        $user->setPhone($createUser->phone)
            ->setFirstName($createUser->firstName)
            ->setLastName($createUser->lastName)
            ->setEmail($createUser->email)
            ->setPassword($this->passwordEncoder->encodePassword($user, $createUser->password));
        $this->update($user, true);
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
