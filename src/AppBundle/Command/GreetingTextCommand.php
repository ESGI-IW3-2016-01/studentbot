<?php

namespace AppBundle\Command;


use AppBundle\Entity\Facebook\GreetingText;
use AppBundle\Repository\GreetingTextRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use GuzzleHttp\Client;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class GreetingTextCommand
 *
 * @author Antoine Cusset <a.cusset@gmail.com>
 * @link https://github.com/acusset
 * @category
 * @license
 * @package AppBundle\Command
 */
class GreetingTextCommand extends ContainerAwareCommand
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
            ->setName("bot:settings:greeting")
            ->setDescription("Enable greeting text")
            ->addOption('show', null, InputOption::VALUE_NONE, "Display active greeting text")
            ->addOption('delete', null, InputOption::VALUE_NONE, "Delete all active greeting(s) text(s)");
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
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $uri = $this->graphVersion . '/me/messenger_profile';

        $greetingText = new GreetingText();
        $greetingText->setText('Hello !');
        $greetingText->setLocale('default');

        $query = [
            'query' => [
                'access_token' => $this->accessToken
            ]
        ];

        if ($input->getOption('show')) {

            $io->title('Listing Greeting Text(s)private');
            $http = 'GET';
            $query['query']['fields'] = 'greeting';

        } elseif ($input->getOption('delete')) {

            $io->title('Deleting Greeting Text(s)private');
            $http = 'DELETE';

            $query['json'] = [
                'fields' => [
                    'greeting'
                ]
            ];

        } else {

            $io->title('Activating Greeting Text(s)private');
            $http = 'POST';

            /** @var GreetingTextRepository $repo */
            $repo = $this->em->getRepository('AppBundle\Entity\Facebook\GreetingText');
            /** @var GreetingText[] $texts */
            $texts = $repo->findBy(['enabled' => true]);
            array_push($texts, $greetingText);

            if ($texts) {
                $texts = array_map(function (GreetingText $element) {
                    return $element->toArray();
                }, $texts);
            } else {
                // TODO Throw exception : no greeting text enabled
                return null;
            }

            $query['json'] = [
                'greeting' => $texts
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
                $data = array_map(function ($e) {
                    return '(' . $e['locale'] . ') ' . $e['text'];
                }, array_pop($json['data'])['greeting']);
                $io->listing($data);
            } else {
                $io->note('No Greeting Text enabled');
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $io->error(sprintf('Error : %s', $e->getMessage()));
        }
    }
}