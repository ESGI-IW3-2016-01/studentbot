<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 25/10/2016
 * Time: 00:13
 */

namespace AppBundle\Command;


use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SubscribeCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('facebook:page:subscribe')
            ->setDescription('Send POST request to facebook API to enable webhook');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        $facebook = new Facebook([
            'app_id' => $container->getParameter('facebook.app_id'), // Replace {app-id} with your app id
            'app_secret' => $container->getParameter('facebook.app_secret'),
            'default_graph_version' => $container->getParameter('facebook.graph_version')
        ]);

        $helper = $facebook->getRedirectLoginHelper();

        try {
            $response = $facebook->sendRequest('POST', 'me/subscribed_apps?access_token=5AIW3ESGI');
            var_dump($response);
        } catch (FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage() . PHP_EOL;
            exit;
        } catch (FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage() . PHP_EOL;
            exit;
        }
    }
}