<?php
/**
 *
 */

namespace AppBundle\Entity\Facebook;


use AppBundle\Entity\School\School;

class QuickReply
{

    const TYPE_LOCATION = 'location';
    const TYPE_TEXT = 'text';

    /**
     * @var
     */
    private $contentType;

    /**
     * @var string $title
     */
    private $title;

    /**
     * @var string $payload
     */
    private $payload;

    /**
     * @var string $imageUrl
     */
    private $imageUrl;

    /**
     * QuickReply constructor.
     * @param $contentType
     * @param string $title
     * @param string $payload
     * @param string $imageUrl
     */
    public function __construct(string $title, string $contentType, string $payload, string $imageUrl = null)
    {
        $this->title = $title;
        $this->contentType = $contentType;
        $this->payload = $payload;
        $this->imageUrl = $imageUrl;
    }

    /**
     * @return mixed
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @param mixed $contentType
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
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

    /**
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    /**
     * @param string $imageUrl
     */
    public function setImageUrl(string $imageUrl)
    {
        $this->imageUrl = $imageUrl;
    }

    public function toArray()
    {
        $array = [];
        switch ($this->contentType) {
            case self::TYPE_LOCATION:
                $array = [
                    'content_type' => 'location'
                ];
                break;
            case self::TYPE_TEXT:
                $array = [
                    'content_type' => 'text',
                    'title' => substr($this->title, 0, 19),
                    'payload' => $this->payload
                ];

                if (isset($this->imageUrl) && !empty($this->imageUrl)) {
                    $array['image_url'] = $this->imageUrl;
                }
                break;
        }
        return $array;
    }

    public static function createFromSchool(School $school)
    {
        $payload = 'SCHOOL_' . strtoupper($school->getName());
        return new static($school->getName(), 'text', $payload);
    }
}