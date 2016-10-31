<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Service\MessageSender;

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

        if ($query->has('hub_challenge')) {
            return new Response($query->get('hub_challenge'));
        }

        $body = json_decode($request->getContent(), true);
        $messageSenderService = $this->container->get('app.message_sender');

        $id = $body['entry'][0]['messaging'][0]['sender']['id'];
        $message = $body['entry'][0]['messaging'][0]['message']['text'];

        error_log("[Message Received] Sender :" . $id . ", message : " . $message);

        $messageSenderService->sendAction('typing_on', $id);
        sleep(2);
        $messageSenderService->sendAction('typing_off', $id);

        return new Response();
    }
}
