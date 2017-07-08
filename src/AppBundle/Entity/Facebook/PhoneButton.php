<?php
/**
 *
 */

namespace AppBundle\Entity\Facebook;


use AppBundle\Entity\Facebook\Enum\ButtonType;

class PhoneButton extends Button
{
    /**
     * @var string $payload
     */
    private $payload;

    /**
     * TODO Add regex to verify number
     */
    const COUNTRY_CODE = '+33';

    /**
     * PhoneButton constructor.
     * Phone Number SHOULD start with +33
     * @param string $title
     * @param string $phoneNumber
     * @internal param $type
     */
    public function __construct(string $title, string $phoneNumber)
    {
        $this->payload = $phoneNumber;
        parent::__construct($title, ButtonType::BUTTON_PHONE);
    }

    /**
     * @return string
     */
    public function getPayload(): string
    {
        return $this->payload;
    }

    /**
     * @param string $payload
     */
    public function setPayload(string $payload)
    {
        $this->payload = $payload;
    }
}