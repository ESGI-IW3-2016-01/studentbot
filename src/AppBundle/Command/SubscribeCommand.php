<?php

namespace AppBundle\Command;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class SubscribeCommand
 *
 * @author Antoine Cusset <a.cusset@gmail.com>
 * @link https://github.com/acusset
 * @category
 * @license
 * @package AppBundle\Command
 */
class SubscribeCommand extends ContainerAwareCommand
{

    /**
     * @var string $graphVersion
     */
    protected $graphVersion;

    /**
     * @var string $accessToken
     */
    protected $accessToken;

    /**
     * @var Client $client
     */
    protected $client;

    protected function configure()
    {
        $this
            ->setName('facebook:page:subscribe')
            ->setDescription('Send POST request to facebook API to enable webhook');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        $this->graphVersion = $container->getParameter('facebook.graph_version');
        $this->accessToken = $container->getParameter('facebook.page_access_token');

        $this->client = new Client(['base_uri' => 'https://graph.facebook.com/']);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $uri = $this->graphVersion . '/me/subscribed_apps';

        $io = new SymfonyStyle($input, $output);
        $io->title('Sending request to Facebook...');

        try {
            /** @var ResponseInterface $response */
            $response = $this->client->post(
                $uri,
                [
                    'query' => [
                        'access_token' => $this->accessToken
                    ]
                ]
            );
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $io->error(sprintf('Error : %s', $e->getMessage()));
        }

        $io->text('[Response] ' . $response->getStatusCode() . ' : ' . $response->getBody());
        $io->success('Webhook subscribed to the page events');

        return null;
    }
}
