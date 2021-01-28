<?php

namespace LevelCredit\LevelCreditApi\Model\Response\SubModel;

use JMS\Serializer\Annotation as Serializer;

abstract class Error
{
    /**
     * @var string
     *
     * @Serializer\Type("string")
     */
    protected $message;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     */
    protected $code;

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }
}
