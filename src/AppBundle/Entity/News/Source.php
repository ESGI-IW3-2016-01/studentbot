<?php

namespace AppBundle\Entity\News;


class Source
{
    /**
     * @var string
     */
    private $_id;

    /**
     * @var string
     */
    private $_name;

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
    private $_category;

    /**
     * @var string
     */
    private $_language;

    /**
     * @var string
     */
    private $_country;

    /**
     * @var string[]
     */
    private $_urlsToLogo;

    /**
     * @var string[]
     */
    private $_sortByAvailable;


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
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->_description = $description;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->_url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->_url = $url;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->_category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->_category = $category;
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->_language;
    }

    /**
     * @param mixed $language
     */
    public function setLanguage($language)
    {
        $this->_language = $language;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->_country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->_country = $country;
    }

    /**
     * @return array
     */
    public function getUrlsToLogo()
    {
        return $this->_urlsToLogo;
    }

    /**
     * @param array $urlsToLogo
     */
    public function setUrlsToLogo($urlsToLogo)
    {
        $this->_urlsToLogo = $urlsToLogo;
    }

    /**
     * @return array
     */
    public function getSortByAvailable() : array
    {
        return $this->_sortByAvailable;
    }

    /**
     * @param array $sortByAvailable
     */
    public function setSortByAvailable($sortByAvailable)
    {
        $this->_sortByAvailable = $sortByAvailable;
    }
}