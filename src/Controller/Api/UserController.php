<?php

namespace App\Controller\Api;

use App\Entity\Input\CreateUser;
use App\Entity\User;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use App\Manager\UserManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Sensio;


/**
 * @Route("/users")
 */
class UserController extends AbstractFOSRestController
{
    private $userManager;

    /**
     * UserController constructor.
     * @param UserManager $userManager
     */
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
     * @return User|Response
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
     * @param Request $request
     * @param createUser $createUser
     * @param User $user
     * @return JsonResponse|User
     */
    public function userUpdate(Request $request, createUser $createUser, User $user)
    {
        $form = $this->createForm('App\Form\UserType', $user);
        $form->handleRequest($request);
        //TODO: validations errors display

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

    public function getErrorMessages(FormInterface $form): array
    {
        $errors = [];

        foreach ($form->getErrors() as $key => $error) {
            if ($form->isRoot()) {
                $errors['#'][] = $error->getMessage();
            } else {
                $errors[] = $error->getMessage();
            }
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = $this->getErrorMessages($child);
            }
        }

        return $errors;
    }
}