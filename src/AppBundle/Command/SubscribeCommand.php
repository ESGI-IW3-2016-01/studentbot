<?php

namespace AppBundle\Command;

use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SubscribeCommand extends ContainerAwareCommand
{

    protected $graphVersion;
    protected $accessToken;
    protected $client;

    protected function configure()
    {
        $this
            ->setName('facebook:page:subscribe')
            ->setDescription('Send POST request to facebook API to enable webhook');
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        $this->graphVersion = $container->getParameter('facebook.graph_version');
        $this->accessToken = $container->getParameter('facebook.page_access_token');

        $this->client = new Client(['base_uri' => 'https://graph.facebook.com/']);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $uri = $this->graphVersion . '/me/subscribed_apps';

        $io = new SymfonyStyle($input, $output);
        $io->title('Sending request to Facebook...');

        try {
            $response = $this->client->post($uri, ['query' => ['access_token' => $this->accessToken]]);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return $io->error(sprintf('Error : %s', $e->getMessage()));
        }

        $io->text('[Response] ' . $response->getStatusCode() . ' : ' . $response->getBody());
        $io->success('Webhook subscribed to the page events');
    }
}
