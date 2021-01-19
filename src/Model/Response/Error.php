<?php

namespace LevelCredit\LevelCreditApi\Model\Response;

use JMS\Serializer\Annotation as Serializer;

class Error
{
    /**
     * @var string
     *
     * @Serializer\Type("string")
     */
    protected $parameter;

    /**
     * @var mixed
     *
     * @Serializer\Type("mixed")
     */
    protected $value;

    /**
     * @var string
     *
     * @Serializer\Type("string")
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
