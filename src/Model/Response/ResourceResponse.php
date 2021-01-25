<?php

namespace LevelCredit\LevelCreditApi\Model\Response;

abstract class ResourceResponse extends BaseResponse
{
    /**
     * @var object
     */
    protected $resource;

    /**
     * @param object $resource
     */
    public function setResource(object $resource): void
    {
        $this->resource = $resource;
    }

    /**
     * @return object
     */
    public function getResource(): object
    {
        return $this->resource;
    }
}
