<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..'),
        ]);
    }

    /**
     * @Route("/webhook", name="webhook")
     */
    public function webhookAction(Request $request)
    {
        $query = $request->query;
        if ($query->has('hub_challenge'))
        {
            return new Response($query->get('hub_challenge'));
        }

        return new Response();
        //TODO : gérer les appels et réponses

    }
}
