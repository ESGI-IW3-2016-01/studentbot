<?php
namespace AppBundle\Controller;

trait TraitNews
{
    private function news()
    {
        $news = $this->container->get('app.news_service');
        $json_data = $news->getArticles();

        $data = json_decode($json_data);

        if ($data->status != 'ok') {
            $res  = "Aucun article disponible.";
            return $res;
        }

        $res = [];
        foreach ($data->articles as $article) {
            $res[] = '\xF0\x9F\x91\xA4 ' . $article->author . ' '. $article->publishedAt;
            $res[] = '\xF0\x9F\x93\x8C ' . $article->title;
            $res[] = '\xF0\x9F\x93\x96 ' . $article->description;
            $res[] = '\xF0\x9F\x8C\x90' . $article->url;
        }

        return $res;
    }
}