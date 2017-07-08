<?php
namespace AppBundle\Controller;

trait TraitNews
{
    private function news()
    {
        $news = $this->container->get('app.news_service');
        $listNews = $news->getArticles();

        if (!$listNews) {
            $res  = "Aucun article disponible.";
            return $res;
        }

        $res = [];
        foreach ($listNews as $news) {
            $res[] = '\xF0\x9F\x91\xA4 ' . $news->getAuthor() . ' '. $news->getPublishedAt();
            $res[] = '\xF0\x9F\x93\x8C ' . $news->getTitle();
            $res[] = '\xF0\x9F\x93\x96 ' . $news->getDescription();
            $res[] = '\xF0\x9F\x8C\x90' . $news->getUrl();
        }

        return $res;
    }
}