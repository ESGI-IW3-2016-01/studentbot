<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class LoadUserData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $userAdmin = new User();
        $userAdmin->setUsername('Jolan');
        $userAdmin->setPlainPassword('test');
        $userAdmin->setFirstName('Jolan');
        $userAdmin->setEnabled(1);
        $userAdmin->setLastName('Levy');
        $userAdmin->setEmail('jolan.levy@gmail.com');
        $userAdmin->setRoles(['ROLE_ADMIN']);
        $manager->persist($userAdmin);

        $userAdmin = new User();
        $userAdmin->setUsername('Antoine');
        $userAdmin->setPlainPassword('test');
        $userAdmin->setFirstName('Antoine');
        $userAdmin->setEnabled(1);
        $userAdmin->setLastName('Cusset');
        $userAdmin->setEmail('a.cusset@gmail.com');
        $userAdmin->setRoles(['ROLE_ADMIN']);
        $manager->persist($userAdmin);

        $userAdmin = new User();
        $userAdmin->setUsername('Alex');
        $userAdmin->setPlainPassword('test');
        $userAdmin->setFirstName('Alexandre');
        $userAdmin->setEnabled(1);
        $userAdmin->setLastName('Morin');
        $userAdmin->setEmail('morinalexandrem@gmail.com');
        $userAdmin->setRoles(['ROLE_ADMIN']);
        $manager->persist($userAdmin);

        $userAdmin = new User();
        $userAdmin->setUsername('isoumare');
        $userAdmin->setPlainPassword('test');
        $userAdmin->setFirstName('Issa');
        $userAdmin->setEnabled(1);
        $userAdmin->setLastName('Soumare');
        $userAdmin->setEmail('soumare.iss@gmail.com');
        $userAdmin->setRoles(['ROLE_ADMIN']);
        $manager->persist($userAdmin);

        $manager->flush();
    }

    public function getOrder() {
        return 1;
    }
}