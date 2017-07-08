<?php
namespace AppBundle\Controller;

trait TraitNews
{
    private function news()
    {
        $news = $this->container->get('app.news_service');
        $listNews = $news->getArticles('IGN');

        if (!$listNews) {
            $res  = "Aucun article disponible.";
            return $res;
        }

        $res = [];
        foreach ($listNews as $news) {
            $res[] = "\xF0\x9F\x93\x8C " . $news->getTitle() . "\x0D\x0A"
                . "\xF0\x9F\x93\x96 " . $news->getDescription().  "\x0D\x0A"
                . "\xF0\x9F\x8C\x90 " . $news->getUrl().  "\x0D\x0A"
                . "\xF0\x9F\x93\xB7 " . $news->getUrlToImage();
        }

        return $res;
    }
}