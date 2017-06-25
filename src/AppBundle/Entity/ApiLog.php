<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use DateTime;

/**
 * Class ApiLog
 * @package AppBundle\Entity
 *
 * @ORM\Table(name="api_log")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ApiLogRepository")
 */
class ApiLog
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="Api")
     * @JoinColumn(name="api_id", referencedColumnName="id")
     */
    private $api;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="integer")
     */
    private $code;

    /**
     * ApiLog constructor.
     * @param Api $api
     * @param int $code
     */
    public function __construct($api, $code)
    {
        $this->api = $api;
        $this->date = new DateTime();
        $this->code = $code;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Api
     */
    public function getApi()
    {
        return $this->api;
    }

    /**
     * @param Api $api
     */
    public function setApi($api)
    {
        $this->api = $api;
    }

    /**
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param int $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }
}