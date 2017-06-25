<?php

namespace AppBundle\Command;

use AppBundle\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Facebook\FacebookResponse;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Facebook\Facebook;
use AppBundle\Entity\User;

class AdminFbCommand extends ContainerAwareCommand
{
    /**
     * @var string
     */
    private $fbSecret;

    /**
     * @var string
     */
    private $fbAppId;

    /**
     * @var Facebook
     */
    private $fb;

    protected function configure()
    {
        $this
            ->setName('facebook:get:admin')
            ->setDescription("Get Facebook Application administrators and populate database");
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->fbAppId = $this->getContainer()->getParameter('facebook.app_id');
        $this->fbSecret = $this->getContainer()->getParameter('facebook.app_secret');

        $this->fb = new Facebook(
            [
                'app_id' => $this->fbAppId,
                'app_secret' => $this->fbSecret
            ]
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /**
         * @var EntityManager $em
         */
        $em = $this->getContainer()->get('Doctrine')->getManager();

        /**
         * @var UserRepository $userRepository
         * @method User findOneByEmail
         */
        $userRepository = $em->getRepository('AppBundle\Entity\User');

        /**
         * @var FacebookResponse $requestRoles
         */
        $requestRoles = $this->fb->get("$this->fbAppId/roles", "$this->fbAppId|$this->fbSecret");

        // on récupère tous les users de l'application ainsi que leur rôle au sein de l'appli
        $roles = $requestRoles->getDecodedBody()['data'];
        $i = 0;

        foreach ($roles as $key => $value) {
            if ($value["role"] == "administrators") {
                $myAdminCall = $this->fb->get($value['user'] . '?fields=email,name,first_name,last_name', $this->fbAppId . '|' . $this->fbSecret);
                $myAdminData = $myAdminCall->getDecodedBody();
                $email = (isset($myAdminData['email'])) ? $myAdminData['email'] : '';

                /**
                 * @var User $existUser
                 */
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
                } elseif ($existUser) {
                    if ($existUser->hasRole('ROLE_SUPER_ADMIN') == false) {
                        $existUser->setRoles(['ROLE_SUPER_ADMIN']);
                        $em->flush();
                    }
                }
            }
        }

        $output->writeln($i . " Administrateurs ajoutés.");
    }
}
