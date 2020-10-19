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
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;

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
     * @OA\Post(
     *     path="/users",
     *     summary="Adds a new user",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="firstName",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="lastName",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="phone",
     *                     type="string"
     *                 ),
     *                 example={"email": "new_user@domain.com", "firstName": "John", "lastName": "Smith", "phone": "+375290000000", "password": "admin2"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     * @Rest\Post("", name="user_create")
     * @Sensio\ParamConverter("createUser", converter = "fos_rest.request_body")
     * @Rest\View(statusCode=201, serializerGroups={"user_get"})
     * @param CreateUser $createUser
     * @return User|Response
     */
    public function userCreate(CreateUser $createUser)
    {
        return $this->userManager->updateUser($createUser, new User());
    }

    /**
     * @Rest\Put("/{id}", name="user_update", requirements={"id" = "\d+"})
     * @Sensio\ParamConverter("createUser", converter = "fos_rest.request_body")
     * @Sensio\ParamConverter("user", converter="doctrine.orm")
     * @Rest\View(serializerGroups={"user_get","user_list"})
     * @param string $id
     * @param createUser $createUser
     * @param User $user
     * @return JsonResponse|User
     */
    public function userUpdate(string $id, createUser $createUser, User $user)
    {
        $createUser->id = $id;
        return $this->userManager->updateUser($createUser, $user);
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