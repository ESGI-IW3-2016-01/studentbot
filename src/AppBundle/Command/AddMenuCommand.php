<?php
/**
 * Created by PhpStorm.
 * User: antoine
 * Date: 05/02/2017
 * Time: 16:19
 */

namespace AppBundle\Command;

use AppBundle\Entity\Facebook\MenuItem;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AddMenuCommand extends ContainerAwareCommand
{
    protected $graphVersion;
    protected $accessToken;
    protected $client;

    protected function configure()
    {
        $this
            ->setName('bot:settings:menu')
            ->setDescription("Enable or disable bot menu")
            ->addOption("list", null, InputOption::VALUE_NONE, "Display active menu")
            ->addOption('delete', 'd', InputOption::VALUE_NONE, "Delete the active menu");
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

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $item1 = new MenuItem('Help', 'postback', null, 'BUTTON_HELP');
        $item2 = new MenuItem('About', 'web_url', 'https://studentbot-morinalexandre.c9users.io/');
        $item3 = new MenuItem('Reset', 'postback', null, 'BUTTON_RESET');

        $io = new SymfonyStyle($input, $output);
        $uri = $this->graphVersion . '/me/thread_settings';
        if ($input->getOption('list')) {
            $io->listing([$item1, $item2, $item3]);
            return;
        } elseif ($input->getOption("delete")) {
            $body = [
                "setting_type" => "call_to_actions",
                "thread_state" => "existing_thread"
            ];
            $message = 'Deleted';
            $http = 'DELETE';
        } else {
            $body = [
                "setting_type" => "call_to_actions",
                "thread_state" => "existing_thread",
                "call_to_actions" => [
                    $item1->toArray(),
                    $item2->toArray(),
                    $item3->toArray(),
                ]
            ];
            $message = 'Enabled';
            $http = 'POST';
        }
        try {
            $response = $this->client->request($http, $uri, ['query' => ['access_token' => $this->accessToken], 'json' => $body]);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return $io->error(sprintf('Error : %s', $e->getMessage()));
        }
        $json = json_decode($response->getBody(), true);
        $io->success('[' . $response->getStatusCode() . '] ' . $json["result"]);
    }
}