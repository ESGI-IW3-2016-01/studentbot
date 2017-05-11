<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function indexAction(Request $request)
    {
        // session_destroy();

        // replace this example code with whatever you need
        return new Response('login page');
    }

    /**
     * @Route("/welcome", name="welcome")
     */
    public function welcomeAction(Request $request)
    {
        var_dump($this->getUser());
        die();

        // replace this example code with whatever you need
        return new Response('welcome');
    }

}
