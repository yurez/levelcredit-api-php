<?php

namespace LevelCredit\LevelCreditApi\Model\Response;

use JMS\Serializer\Annotation as Serializer;

class Error
{
    /**
     * @Serializer\Type("string")
     *
     * @var string
     */
    protected $parameter;

    /**
     * @Serializer\Type("string")
     *
     * @var string
     */
    protected $value;

    /**
     * @Serializer\Type("string")
     *
     * @var string
     */
    protected $message;

    /**
     * @return string
     */
    public function getParameter(): string
    {
        return $this->parameter;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
