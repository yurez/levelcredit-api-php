<?php

namespace LevelCredit\LevelCreditApi\Model\Response;

abstract class BaseResponse
{
    /**
     * @var int
     */
    protected $statusCode;

    /**
     * @var ErrorCollection
     */
    protected $errors;

    public function __construct()
    {
        $this->errors = new ErrorCollection();
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    public function getErrors(): ErrorCollection
    {
        return $this->errors;
    }

    public function setErrors(ErrorCollection $collection): void
    {
        $this->errors = $collection;
    }
}
