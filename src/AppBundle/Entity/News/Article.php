<?php

namespace AppBundle\Entity\News;

use DateTime;
use DateTimeZone;

class Article
{

    /**
     * @var string
     */
    private $_author;

    /**
     * @var string
     */
    private $_title;

    /**
     * @var string
     */
    private $_description;

    /**
     * @var string
     */
    private $_url;

    /**
     * @var string
     */
    private $_urlToImage;

    /**
     * @var DateTime
     */
    private $_publishedAt;

    /**
     * Source constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        if ($data) {
            foreach ($data as $key => $value) {
                $method = 'set' . ucfirst($key);
                if (method_exists($this, $method)) {
                    $this->$method($value);
                }
            }
        }
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->_author;
    }

    /**
     * @param string $author
     */
    public function setAuthor(string $author)
    {
        $this->_author = $author;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->_title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->_title = $title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->_description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->_description = $description;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->_url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url)
    {
        $this->_url = $url;
    }

    /**
     * @return string
     */
    public function getUrlToImage(): string
    {
        return $this->_urlToImage;
    }

    /**
     * @param string $urlToImage
     */
    public function setUrlToImage(string $urlToImage)
    {
        $this->_urlToImage = $urlToImage;
    }

    /**
     * @return DateTime
     */
    public function getPublishedAt(): DateTime
    {
        return $this->_publishedAt;
    }

    /**
     * @param string $publishedAt
     */
    public function setPublishedAt(string $publishedAt)
    {
        $this->_publishedAt = new DateTime($publishedAt, new DateTimeZone('Europe/Paris'));
    }
}