<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Manager\UserManager;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
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
     * @Rest\View(statusCode=201)
     */
    public function userCreate($user)
    {
        //TODO: add param converter for user creation
       return $this->userManager->create($user);
    }

    /**
     * @Rest\Get("/{id}", name="user_get", requirements={"id" = "\d+"})
     * @Rest\View(serializerGroups={"user_get"})
     * @param $id
     * @return User|null
     */
    public function userGet($id)
    {
        return $this->userManager->find($id);
    }


    /**
     * @Rest\Delete("/{id}", name="user_delete")
     * @Rest\View(statusCode=204)
     * @param $id
     * @return array
     */
    public function userDelete($id): array
    {
        return $this->userManager->delete($id);
    }

    /**
     * @Rest\Put("/{id}", name="user_update")
     * @Sensio\ParamConverter("user", converter="doctrine.orm")
     * @param User $user
     * @return User
     */
    public function userUpdate(User $user)
    {
        return $user;
    }

}