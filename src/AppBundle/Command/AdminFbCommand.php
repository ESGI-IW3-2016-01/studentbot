<?php

namespace AppBundle\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Facebook\Facebook;
use AppBundle\Entity\User;

class AdminFbCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('admin:fb')
            ->setDescription("add to database administrators fb of student app");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('Doctrine')->getManager();
        $userRepository = $em->getRepository('AppBundle\Entity\User');

        $fbAppId = $this->getContainer()->getParameter('facebook.app_id');
        $fbSecret = $this->getContainer()->getParameter('facebook.app_secret');

        $fb = new Facebook([
            'app_id' => $fbAppId,
            'app_secret' => $fbSecret,
        ]);

        $requestRoles = $fb->get($fbAppId."/roles", $fbAppId.'|'.$fbSecret);
        // on récupère tous les users de l'application ainsi que leur rôle au sein de l'appli
        $roles = $requestRoles->getDecodedBody()['data'];
        $i = 0;

        foreach($roles as $key => $value){
            if ($value["role"] == "administrators"){
                $myAdminCall = $fb->get($value['user'].'?fields=email,name,first_name,last_name', $fbAppId.'|'.$fbSecret);
                $myAdminData = $myAdminCall->getDecodedBody();
                $email = (isset($myAdminData['email'])) ? $myAdminData['email'] : '';
                
                $existUser = $userRepository->findOneByEmail($email);

                if (!$existUser && !empty($email)) {
                    $user = new User();
                    $user->setFacebookId($myAdminData['id']);
                    $user->setUsername($myAdminData['name']);
                    $user->setPlainPassword('test');
                    $user->setFirstName($myAdminData['first_name']);
                    $user->setEnabled(1);
                    $user->setLastName($myAdminData['last_name']);
                    $user->setEmail($email);
                    $user->setRoles(['ROLE_SUPER_ADMIN']);
                    $em->persist($user);
                    $em->flush();
                    $i++;
                }
            }
        }

        $output->writeln($i." Administrateurs ajoutés.");
    }
}