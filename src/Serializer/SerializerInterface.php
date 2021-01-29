<?php

namespace LevelCredit\LevelCreditApi\Serializer;

use LevelCredit\LevelCreditApi\Exception\SerializerException;
use LevelCredit\LevelCreditApi\Model\Response\BaseResponse;
use Psr\Http\Message\ResponseInterface;

interface SerializerInterface
{
    /**
     * @param object $requestModel
     * @return string
     * @throws SerializerException
     */
    public function serializeRequest(object $requestModel): string;

    /**
     * @param object $query
     * @return array
     * @throws SerializerException
     */
    public function serializeQuery(object $query): array;

    /**
     * @param ResponseInterface $response
     * @param BaseResponse $responseModel
     * @param string|null $resourceClassName
     * @return BaseResponse
     * @throws SerializerException
     */
    public function deserializeResponse(
        ResponseInterface $response,
        BaseResponse $responseModel,
        string $resourceClassName = null
    ): BaseResponse;
}
