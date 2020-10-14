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
     * @Rest\View()
     */
    public function userList()
    {
        return $this->userManager->all();
    }

    /**
     * @Rest\Post("", name="user_create")
     * @Sensio\ParamConverter("user", converter="doctrine.orm")
     * @param User $user
     */
    public function userCreate($user)
    {
//        //TODO user manager method (create)
//        $user = $this->userManager->create($request);
//        $status = $user ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
//
//        return new JsonResponse([],Response::HTTP_CREATED);
    }

    /**
     * @Rest\Get("/{id}", name="user_get", requirements={"id" = "\d+"})
     * @Sensio\ParamConverter("user", converter="doctrine.orm")
     * @Rest\View(serializerGroups={"default", "user"})
     *
     * @param User $user
     * @return User
     */
    public function userGet(User $user)
    {
        return $this->userManager->find($user);
    }


    /**
     * @Rest\Delete("/{id}", name="user_delete")
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