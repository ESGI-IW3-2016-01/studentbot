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
            $str = '';

            if ($news->getAuthor()) {
                $str .= "\xF0\x9F\x91\xA4 " . $news->getAuthor() . "\x0D\x0A";
            }

            if ($news->getTitle()) {
                $str .= "\xF0\x9F\x93\x8C " . $news->getTitle() . "\x0D\x0A";
            }

            if ($news->getDescription()) {
                $str .= "\xF0\x9F\x93\x96 " . $news->getDescription().  "\x0D\x0A";
            }

            if ($news->getUrl()) {
                $str .= "\xF0\x9F\x8C\x90 " . $news->getUrl().  "\x0D\x0A";
            }

            if ($news->getUrlToImage()) {
                $str .= "\xF0\x9F\x93\xB7 " . $news->getUrlToImage();
            }
            $res[] = $str;
        }

        return $res;
    }
}