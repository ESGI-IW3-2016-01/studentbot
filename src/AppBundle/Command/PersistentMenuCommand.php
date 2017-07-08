<?php

namespace AppBundle\Command;

use AppBundle\Entity\Facebook\MenuItem;
use AppBundle\Repository\MenuItemRepository;
use Doctrine\ORM\EntityManager;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class PersistentMenuCommand
 *
 * @author Antoine Cusset <a.cusset@gmail.com>
 * @link https://github.com/acusset
 * @category
 * @license
 * @package AppBundle\Command
 */
class PersistentMenuCommand extends ContainerAwareCommand
{
    protected $graphVersion;
    protected $accessToken;

    /**
     * @var Client $client
     */
    protected $client;

    /**
     * @var EntityManager $em
     */
    private $em;

    protected function configure()
    {
        $this
            ->setName('bot:settings:menu')
            ->setDescription("Enable or disable Persistent Menu")
            ->addOption('show', null, InputOption::VALUE_NONE, 'Display active menu items')
            ->addOption('delete', null, InputOption::VALUE_NONE, 'Delete persistent menu');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $this->em = $this->getContainer()->get('Doctrine')->getManager();
        $this->graphVersion = $container->getParameter('facebook.graph_version');
        $this->accessToken = $container->getParameter('facebook.page_access_token');
        $this->client = new Client(['base_uri' => 'https://graph.facebook.com/']);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return null|void
     */
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
            $io->title('Listing active Menu Items');
            $http = 'GET';
            $query['query']['fields'] = 'persistent_menu';
        } elseif ($input->getOption('delete')) {
            $io->title('Deleting active Menu Items');
            $http = 'DELETE';

            $query['json'] = [
                'fields' => [
                    'persistent_menu'
                ]
            ];

        } else {
            $io->title('Activating Menu Items');
            $http = 'POST';

            /** @var MenuItemRepository $repo */
            $repo = $this->em->getRepository('AppBundle\Entity\Facebook\MenuItem');
            /** @var MenuItem[] $items */
            $items = $repo->findBy(['enabled' => true], null, 3);

            if ($items) {
                $items = array_map(function (MenuItem $element) {
                    return $element->toArray();
                }, $items);
            } else {
                // TODO Throw exception : no item enabled in admin
                $io->note('No Greeting Text enabled in configuration');
                return null;
            }

            $query['json'] = [
                'persistent_menu' => [
                    [
                        'locale' => 'default',
                        'composer_input_disabled' => false,
                        'call_to_actions' => $items
                    ]
                ]
            ];
        }

        try {
            $response = $this->client->request(
                $http,
                $uri,
                $query
            );

            if ($response->getBody()) {
                $json = json_decode((string)$response->getBody(), true);
            }
            if (isset($json['result']) && 'success' === $json['result']) {
                $io->success(sprintf('[%s] Facebook Greeting Text set.', $response->getStatusCode()));
            } elseif (isset($json['data']) && count($json['data']) > 0) {
                $list = array_pop(array_pop($json['data'])['persistent_menu'])['call_to_actions'];
                $list = array_map(function ($e) {
                    if ($e['type'] == 'web_url') {
                        return $e['title'] . ' : (' . $e['type'] . ') ' . $e['url'];
                    } elseif ($e['type'] == 'postback') {
                        return $e['title'] . ' : (' . $e['type'] . ') ' . $e['payload'];
                    } else {
                        return '';
                    }
                }, $list);
                $io->listing($list);
            } else {
                $io->note('No Greeting Text enabled');
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $io->error(sprintf('Error : %s', $e->getMessage()));
        }
    }
}