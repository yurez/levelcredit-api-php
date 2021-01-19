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

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     */
    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @return ErrorCollection
     */
    public function getErrors(): ErrorCollection
    {
        return $this->errors;
    }

    /**
     * @param ErrorCollection $collection
     */
    public function setErrors(ErrorCollection $collection): void
    {
        $this->errors = $collection;
    }
}
