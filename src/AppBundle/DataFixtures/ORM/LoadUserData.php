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
        $userAdmin->setUsername('Ralph');
        $userAdmin->setPassword('test');
        $userAdmin->setFirstName('Jolan');
        $userAdmin->setLastName('Levy');
        $userAdmin->setEmail('jolan.levy@gmail.com');
        $manager->persist($userAdmin);

        $userAdmin = new User();
        $userAdmin->setUsername('Antoine');
        $userAdmin->setPassword('test');
        $userAdmin->setFirstName('Antoine');
        $userAdmin->setLastName('Cusset');
        $userAdmin->setEmail('a.cusset@gmail.com');
        $manager->persist($userAdmin);

        $userAdmin = new User();
        $userAdmin->setUsername('Alex');
        $userAdmin->setPassword('test');
        $userAdmin->setFirstName('Alexandre');
        $userAdmin->setLastName('Morin');
        $userAdmin->setEmail('morinalexandrem@gmail.com');
        $manager->persist($userAdmin);

        $manager->flush();
    }

    public function getOrder() {
        return 1;
    }
}