<?php

namespace App\Controller\Api;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Sensio;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Security;

class AuthController extends AbstractFOSRestController
{

    /**
     * @OA\Post(
     *     path="/login_check",
     *     summary="Login",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="username",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 example={"username": "onekit@gmail.com", "password": "admin"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     * @Rest\Post("/login_check", name = "api_login_check")
     * @Security(name="Bearer")
     */
    public function loginCheck()
    {
    }

}