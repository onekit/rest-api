<?php

namespace App\Controller\Api;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Sensio;


class AuthController extends AbstractFOSRestController
{
    /**
     * @Rest\Get("/login", name="api_login")
     */
    public function login()
    {

    }

    /**
     * @Rest\Post("/login_check", name = "api_login_check")
     */
    public function loginCheck()
    {
    }

    /**
     * @Rest\Get("/logout", name = "api_logout")
     * @return bool
     */
    public function logout(): bool
    {
        return true;
    }
}