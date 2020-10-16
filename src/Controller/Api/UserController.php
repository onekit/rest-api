<?php

namespace App\Controller\Api;

use App\Entity\Input\CreateUser;
use App\Entity\User;
use App\Manager\UserManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Sensio;


/**
 * @Route("/users")
 */
class UserController extends AbstractFOSRestController
{
    private $userManager;
    public function __construct(UserManager $userManager) {
        $this->userManager = $userManager;
    }

    /**
     * @Rest\Get("", name="user_list")
     * @Rest\View(serializerGroups={"user_list"})
     */
    public function userList()
    {
        return $this->userManager->all();
    }

    /**
     * @Rest\Post("", name="user_create")
     * @Sensio\ParamConverter("createUser", converter = "fos_rest.request_body")
     * @Rest\View(statusCode=201, serializerGroups={"user_get"})
     * @param CreateUser $createUser
     * @return User
     */
    public function userCreate(CreateUser $createUser)
    {
       return $this->userManager->createUser($createUser);
    }

    /**
     * @Rest\Get("/{id}", name="user_get", requirements={"id" = "\d+"})
     * @Rest\View(serializerGroups={"user_get"})
     * @param $id
     * @return User|null
     */
    public function userGet($id): ?User
    {
        return $this->userManager->find($id);
    }

    /**
     * @Rest\Put("/{id}", name="user_update", requirements={"id" = "\d+"})
     * @Sensio\ParamConverter("createUser", converter = "fos_rest.request_body")
     * @Sensio\ParamConverter("user", converter="doctrine.orm")
     * @Rest\View(serializerGroups={"user_get","user_list"})
     * @param createUser $createUser
     * @param User $user
     * @return User
     */
    public function userUpdate(createUser $createUser, User $user): User
    {
        return $this->userManager->updateUser($user, $createUser);
    }

    /**
     * @Rest\Delete("/{id}", name="user_delete")
     * @Rest\View(statusCode=204)
     * @Sensio\IsGranted("ROLE_ADMIN")
     * @param $id
     * @return JsonResponse
     */
    public function userDelete($id): JsonResponse
    {
        return $this->userManager->delete($id);
    }

}