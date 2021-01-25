<?php

namespace LevelCredit\LevelCreditApi\Model\Response\Resource;

use JMS\Serializer\Annotation as Serializer;

class Sync extends BaseResource
{
    /**
     * @var string
     *
     * @Serializer\Type("string")
     */
    protected $status;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     */
    protected $message;

    /**
     * @return string
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }
}
