<?php

namespace LevelCredit\LevelCreditApi;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Serializer as JMSSerializer;
use JMS\Serializer\SerializerBuilder;
use LevelCredit\LevelCreditApi\Exception\SerializerException;
use LevelCredit\LevelCreditApi\Model\Response\BaseResponse;
use LevelCredit\LevelCreditApi\Model\Response\CollectionResponse;
use LevelCredit\LevelCreditApi\Model\Response\ErrorCollection;
use LevelCredit\LevelCreditApi\Model\Response\ResourceResponse;
use Psr\Http\Message\ResponseInterface;

class Serializer
{
    protected const FAILED_STATUS_CODE = 400;

    /**
     * @var JMSSerializer
     */
    protected $serializer;

    public function __construct(JMSSerializer $serializer)
    {
        $this->serializer = $serializer;
    }

    public static function create(JMSSerializer $serializer = null): self
    {
        $serializer || $serializer = SerializerBuilder::create()->build();

        return new static($serializer);
    }

    /**
     * @throws SerializerException
     */
    public function serializeRequest(object $requestModel): string
    {
        try {
            return $this->serializer->serialize($requestModel, 'json');
        } catch (\Throwable $e) {
            throw new SerializerException('Unable serialize request: ' . $e->getMessage());
        }
    }

    /**
     * @throws SerializerException
     */
    public function serializeQuery(object $query): array
    {
        try {
            return $this->serializer->toArray($query);
        } catch (\Throwable $e) {
            throw new SerializerException('Unable serialize query: ' . $e->getMessage());
        }
    }

    /**
     * @throws SerializerException
     */
    public function deserializeResponse(
        ResponseInterface $response,
        BaseResponse $responseModel,
        string $resourceClassName = null
    ): BaseResponse {
        $body = (string)$response->getBody();
        $responseModel->setStatusCode($response->getStatusCode());

        if (empty($body) || !$resourceClassName) {
            // empty response

            return $responseModel;
        } elseif ($response->getStatusCode() >= static::FAILED_STATUS_CODE) {
            // failed response
            try {
                $result = $this->serializer->deserialize(
                    $body,
                    'ArrayCollection<LevelCredit\LevelCreditApi\Model\Response\Error>',
                    'json'
                );
            } catch (\Throwable $e) {
                throw new SerializerException('Unable deserialize failed response: ' . $e->getMessage());
            }

            if ($result instanceof ArrayCollection) {
                $result = $result->toArray();
            }

            $responseModel->setErrors(new ErrorCollection($result ?: []));

            return $responseModel;
        } elseif ($responseModel instanceof CollectionResponse) {
            // collection response
            try {
                $result = $this->serializer->deserialize(
                    $body,
                    'ArrayCollection<' . $resourceClassName . '>',
                    'json'
                );
            } catch (\Throwable $e) {
                throw new SerializerException('Unable deserialize collection response: ' . $e->getMessage());
            }

            if (!$result instanceof ArrayCollection) {
                throw new SerializerException('Unable deserialize collection response: Get incorrect result');
            }

            $responseModel->setElements($result);

            if ($totalCount = (int)$response->getHeader('X-Total-Count')) {
                $responseModel->setTotalCount($totalCount);
            } else {
                $responseModel->setTotalCount($result->count());
            }

            return $responseModel;
        } elseif ($responseModel instanceof ResourceResponse) {
            try {
                $resourceModel = $this->serializer->deserialize($body, $resourceClassName, 'json');
            } catch (\Throwable $e) {
                throw new SerializerException('Unable deserialize resource response: ' . $e->getMessage());
            }


            if (!is_a($resourceModel, $resourceClassName)) {
                throw new SerializerException('Unable deserialize resource response: Get incorrect result');
            }

            $responseModel->setResource($responseModel);

            return $responseModel;
        }

        throw new SerializerException('Unable deserialize response: Unexpected behavior');
    }
}
