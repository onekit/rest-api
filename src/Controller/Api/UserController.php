<?php

namespace App\Controller\Api;

use App\Entity\Input\CreateUser;
use App\Entity\User;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use App\Manager\UserManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Sensio;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;


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
     * @Rest\Post("", name="user_create")
     * @Sensio\ParamConverter("createUser", converter = "fos_rest.request_body")
     * @Rest\View(statusCode=201, serializerGroups={"user_get"})
     * @param CreateUser $createUser
     * @param ValidatorInterface $validator
     * @return User|Response
     */
    public function userCreate(CreateUser $createUser, ValidatorInterface $validator)
    {
        $user = new User();
        $this->userManager->assignUser($user, $createUser);
        $constraints = $validator->validate($user);
        if ($constraints->count()) {
            return new JsonResponse(['errors' => $this->handleError($constraints)], 400);
        }
        return $this->userManager->update($user, true);
    }

    /**
     * @Rest\Put("/{id}", name="user_update", requirements={"id" = "\d+"})
     * @Sensio\ParamConverter("createUser", converter = "fos_rest.request_body")
     * @Sensio\ParamConverter("user", converter="doctrine.orm")
     * @Rest\View(serializerGroups={"user_get","user_list"})
     * @param string $id
     * @param createUser $createUser
     * @param User $user
     * @param ValidatorInterface $validator
     * @return JsonResponse|User
     */
    public function userUpdate(string $id, createUser $createUser, User $user, ValidatorInterface $validator, Security $security)
    {
        if ($security->getUser()->getId() != $id) {
            return new JsonResponse(['errors' => 'Access denied. You can edit only your own account.'], 403);
        }

        $user = $this->userManager->assignUser($user, $createUser);
        $constraints = $validator->validate($user);

        if ($constraints->count()) {
            return new JsonResponse(['errors' => $this->handleError($constraints)], 400);
        }

        return $this->userManager->update($user, true);
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