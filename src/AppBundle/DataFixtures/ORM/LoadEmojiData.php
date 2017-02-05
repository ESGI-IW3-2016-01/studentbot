<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Facebook\Emoji;

class LoadEmojiData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $emoji = new Emoji();
        $emoji->setUnicode('U+26BD');
        $emoji->setBytes('\xE2\x9A\xBD');
        $emoji->setDescription('SOCCER BALL');
        $manager->persist($emoji);

        $emoji = new Emoji();
        $emoji->setUnicode('U+1F3C0');
        $emoji->setBytes('\xF0\x9F\x8F\x80');
        $emoji->setDescription('BASKETBALL AND HOOP');
        $manager->persist($emoji);

        $emoji = new Emoji();
        $emoji->setUnicode('U+1F3C8');
        $emoji->setBytes('\xF0\x9F\x8F\x88');
        $emoji->setDescription('AMERICAN FOOTBALL');
        $manager->persist($emoji);
        
        $emoji = new Emoji();
        $emoji->setUnicode('U+1F3BE');
        $emoji->setBytes('\xF0\x9F\x8E\xBE');
        $emoji->setDescription('TENNIS RACQUET AND BALL');
        $manager->persist($emoji);
        
        $emoji = new Emoji();
        $emoji->setUnicode('U+1F3CA');
        $emoji->setBytes('\xF0\x9F\x8F\x8A');
        $emoji->setDescription('SWIMMER');
        $manager->persist($emoji);
        
        $emoji = new Emoji();
        $emoji->setUnicode('U+1F31E');
        $emoji->setBytes('\xF0\x9F\x8C\x9E');
        $emoji->setDescription('SUN WITH FACE');
        $manager->persist($emoji);
        
        $emoji = new Emoji();
        $emoji->setUnicode('U+26C5');
        $emoji->setBytes('\xE2\x9B\x85');
        $emoji->setDescription('SUN BEHIND CLOUD');
        $manager->persist($emoji);

        $manager->flush();
    }

    public function getOrder() {
        return 1;
    }
}

