<?php
/**
 *
 */

namespace AppBundle\Command;


use Psr\Http\Message\ResponseInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use GuzzleHttp\Client;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class GetStartedButtonCommand
 * Enable Get Started Button
 *
 * @see https://developers.facebook.com/docs/messenger-platform/messenger-profile/get-started-button
 * @author Antoine Cusset <a.cusset@gmail.com>
 * @link https://github.com/acusset
 * @category
 * @license
 * @package AppBundle\Command
 */
class GetStartedButtonCommand extends ContainerAwareCommand
{
    const PAYLOAD = 'GET_STARTED_BUTTON_PAYLOAD';

    private $graphVersion;
    private $accessToken;
    /**
     * @var Client $client
     */
    private $client;

    protected function configure()
    {
        $this
            ->setName('bot:settings:start-button')
            ->setDescription('Enable bot "Get started" button')
            ->addOption('show', null, InputOption::VALUE_NONE, 'Display the Get Started Button')
            ->addOption('delete', null, InputOption::VALUE_NONE, 'Delete the Get Started Button');
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
        $io = new SymfonyStyle($input, $output);
        $uri = $this->graphVersion . '/me/messenger_profile';

        $query = [
            'query' => [
                'access_token' => $this->accessToken
            ]
        ];

        if ($input->getOption('show')) {
            $httpVerb = 'GET';
            $query['query']['fields'] = 'get_started';
        } elseif ($input->getOption('delete')) {
            $httpVerb = 'DELETE';
            $query['json'] = [
                'fields' => [
                    'get_started'
                ]
            ];
        } else {
            $httpVerb = 'POST';
            $query['json'] = [
                'get_started' => [
                    'payload' => self::PAYLOAD
                ]
            ];
        }

        try {
            /** @var ResponseInterface $response */
            $response = $this->client->request(
                $httpVerb,
                $uri,
                $query
            );

            if ($response->getBody()) {
                $json = json_decode((string)$response->getBody(), true);
            }

            if (isset($json['result']) && 'success' === $json['result']) {
                $io->success(sprintf('[%s] Facebook Get Started Button enabled', $response->getStatusCode()));
            } elseif (isset($json['data'])) {
                $io->success(sprintf('[%s] Facebook Get Started Button is enabled with payload "%s"', $response->getStatusCode(), array_pop($json['data'])['get_started']['payload']));
            }

        } catch (Exception $e) {
            // Throw error when trying to delete button with persistent menu enabled
            error_log($e->getMessage());
            $io->error(sprintf('Error : %s', $e->getMessage()));
        }
    }
}