<?php
/**
 *
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Facebook\GreetingText;
use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as AdminBaseController;

class GreetingTextController extends AdminBaseController
{

    protected function prePersistEntity($greetingText)
    {
        /** @var GreetingText $text */
        $text = $this->em
            ->getRepository('AppBundle:Facebook\GreetingText')
            ->findOneBy(['locale' => $greetingText->getLocale()->getId()]);

        if($text) {
            $text->setText($greetingText->getText());
            $this->em->remove($text);
            $this->em->flush();
        };
    }
}