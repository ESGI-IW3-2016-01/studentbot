<?php

namespace AppBundle\Command;


use AppBundle\Entity\Api;
use AppBundle\Repository\ApiRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ApiStatusCommand extends ContainerAwareCommand
{

    /**
     * @var string
     */
    private $id;

    /**
     * @var  string
     */
    private $action;

    /**
     * @var array
     */
    private $actions = ['enable', 'disable'];

    protected function configure()
    {
        $this
            ->setName('api:status')
            ->setDescription('Enable or disable api\'s')
            ->addArgument('action', InputArgument::OPTIONAL, 'Action to do (enable or disable)')
            ->addArgument('id', InputArgument::OPTIONAL, 'The API id');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->id = $input->getArgument('id');
        $this->action = $input->getArgument('action');

        $io = new SymfonyStyle($input, $output);
        if (isset($this->action) && !in_array($this->action, $this->actions)) {
            $io->error('Action should be either \'enable\' or \'disable\'');
            exit;
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        /**
         * @var EntityManager $em
         */
        $em = $this->getContainer()->get('Doctrine')->getManager();
        /**
         * @var ApiRepository $repository
         */
        $repository = $em->getRepository('AppBundle\Entity\Api');

        if (empty($this->action) && empty($this->id)) {
            $apis = $repository->findAll();
            $io->listing($apis);
            return;
        }

        if (!empty($this->action) && !empty($this->id)) {
            /**
             * @var Api $api
             */
            $api = $repository->findOneBy(['id' => $this->id]);
            if ($api) {
                $api->setEnabled('enable' === $this->action);
                $em->flush();
            } else {
                $io->error('API not found');
                return;
            }
            $io->success($api);
        } else {
            /**
             * @var array $apis
             */
            $apis = $repository->findAll();
            /**
             * @var Api $api
             */
            foreach ($apis as $api) {
                $api->setEnabled($this->action == 'enable');
            }
            $em->flush();
            $io->success("All APIs have been $this->action\d !");
        }
    }
}