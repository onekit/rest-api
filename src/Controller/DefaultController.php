<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends AbstractController
{
    public function index()
    {
        return new Response('<h1>REST API</h1>',200);
    }
}