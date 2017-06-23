<?php

namespace AppBundle\Service;


use AppBundle\Entity\News\Article;
use AppBundle\Entity\News\Source;
use AppBundle\Event\ApiEvent;
use GuzzleHttp\Client;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class NewsService
{

    private $apiKey;
    private $dispatcher;
    private $categories = ['technology', 'gaming', 'business', 'entertainment'];
    const NAME = 'news';


    public function __construct(String $apiKey, EventDispatcherInterface $eventDispatcher)
    {
        $this->apiKey = $apiKey;
        $this->dispatcher = $eventDispatcher;
        $this->client = new Client([
            'base_uri' => 'https://newsapi.org/v1/'
        ]);
    }

    public function getSources($category = null, $language = null, $country = null): array
    {
        $sources = [];

        // null parameters will not appear on the request
        $params = http_build_query([
            'category' => $category,
            'language' => $language,
            'country' => $country
        ]);

        try {
            $response = $this->client->get('sources', ['query' => $params]);

            error_log('[Guzzle Response] ' . $response->getStatusCode() . ' : ' . $response->getBody());
            $this->dispatcher->dispatch(ApiEvent::NAME, new ApiEvent(self::NAME, $response->getStatusCode()));

            $body = json_decode($response->getBody(), true);

            if ('ok' === $body['status'] && 0 < count($body['sources'])) {
                foreach ($body['sources'] as $source) {
                    $sources[] = new Source($source);
                }
            }

        } catch (\Exception $e) {
            error_log($e->getMessage());
        }

        return $sources;
    }

    public function getArticles($source = 'techcrunch', $sort = 'top')
    {
        $articles = [];

        // null parameters will not appear on the request
        $uri = http_build_query([
            'source' => $source,
            'sortBy' => $sort,
            'apiKey' => $this->apiKey
        ]);

        try {
            $response = $this->client->get('articles', ['query' => $uri]);

            error_log('[Guzzle Response] ' . $response->getStatusCode() . ' : ' . $response->getBody());
            $this->dispatcher->dispatch(ApiEvent::NAME, new ApiEvent(self::NAME, $response->getStatusCode()));

            $body = json_decode($response->getBody(), true);

            if ('ok' === $body['status'] && 0 < count($body['articles'])) {
                foreach ($body['articles'] as $article) {
                    $articles[] = new Article($article);
                }
            }

        } catch (\Exception $e) {
            error_log($e->getMessage());
        }

        return $articles;

    }
}