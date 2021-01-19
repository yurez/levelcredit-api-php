<?php

namespace LevelCredit\LevelCreditApi\Model\Request;

abstract class BaseRequest
{
    /**
     * @return static
     */
    public static function create(): self
    {
        return new static();
    }
}
