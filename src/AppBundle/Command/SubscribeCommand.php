<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SubscribeCommand extends ContainerAwareCommand
{

    protected $graphVersion;
    protected $accessToken;

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
        $this->accessToken  = $container->getParameter('facebook.page_access_token');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = \sprintf("curl -X POST \"https://graph.facebook.com/%s/me/subscribed_apps?access_token=%s\"  2>/dev/null", $this->graphVersion, $this->accessToken);
        exec($command, $commandOutput, $returnCode);

        $commandOutput = json_decode($commandOutput[0],true);

        $io = new SymfonyStyle($input, $output);
        $io->title('Sending request to Facebook...');

        if(isset($commandOutput["error"]))
        {
            return $io->error(sprintf('Error : %s',$commandOutput["error"]["message"]));
        }

        $io->success('Webhook subscribed to the page events');
    }
}