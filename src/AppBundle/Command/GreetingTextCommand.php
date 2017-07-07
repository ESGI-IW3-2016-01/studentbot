<?php

namespace AppBundle\Command;


use AppBundle\Repository\GreetingTextRepository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use GuzzleHttp\Client;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;

class GreetingTextCommand extends ContainerAwareCommand
{

    protected $graphVersion;
    protected $accessToken;
    protected $client;
    private $id;

    protected function configure()
    {
        $this
            ->setName("bot:settings:greeting")
            ->addOption("delete", "d", InputOption::VALUE_NONE, "Delete the active greeting text")
            ->addOption("list", "l", InputOption::VALUE_NONE, "Display active greeting text")
            ->setDescription("Enable greeting text");
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
        $io = new SymfonyStyle($input, $output);
        $uri = $this->graphVersion . '/me/thread_settings';

        if ($input->getOption("list")) {
            $em = $this->getContainer()->get('Doctrine')->getManager();
            /**
             * @var GreetingTextRepository $repo
             */
            $repo = $em->getRepository('AppBundle\Entity\Facebook\GreetingText');
            $texts = $repo->findAll(['isEnabled' => true])->getText();
            $io->listing($texts);
        }

        if ($input->getOption("delete")) {
            $io->title('Deleting greeting text...');
            $body = [
                "setting_type" => "greeting"
            ];
            $message = 'Greeting text disabled';
            $http = 'DELETE';
        } else {
            $io->title('Activating greeting text...');
            $em = $this->getContainer()->get('Doctrine')->getManager();
            /**
             * @var GreetingTextRepository $repo
             */
            $repo = $em->getRepository('AppBundle\Entity\Facebook\GreetingText');
            $text = $repo->findOneBy(['isEnabled' => true])->getText();

            $body = [
                "setting_type" => "greeting",
                "greeting" => [
                    "text" => $text
                ]
            ];
            $message = 'Greeting text enabled';
            $http = 'POST';
        }

        try {
            $response = $this->client->request($http, $uri, ['query' => ['access_token' => $this->accessToken], 'json' => $body]);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return $io->error(sprintf('Error : %s', $e->getMessage()));
        }
        $io->text('[Response] ' . $response->getStatusCode() . ' : ' . $response->getBody());
        $io->success($message);
    }
}