<?php

namespace LevelCredit\LevelCreditApi\Serializer;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\Serializer as JMSSerializer;
use JMS\Serializer\SerializerBuilder;
use LevelCredit\LevelCreditApi\Exception\SerializerException;
use LevelCredit\LevelCreditApi\Model\Response\BaseResponse;
use LevelCredit\LevelCreditApi\Model\Response\CollectionResponse;
use LevelCredit\LevelCreditApi\Model\Response\EmptyResponse;
use LevelCredit\LevelCreditApi\Model\Response\Error;
use LevelCredit\LevelCreditApi\Model\Response\ErrorCollection;
use LevelCredit\LevelCreditApi\Model\Response\ResourceResponse;
use Psr\Http\Message\ResponseInterface;

class Serializer implements SerializerInterface
{
    protected const FAILED_STATUS_ENTRY_POINT = 400;

    /**
     * @var JMSSerializer
     */
    protected $serializer;

    /**
     * @param JMSSerializer $serializer
     */
    public function __construct(JMSSerializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param JMSSerializer|null $serializer
     * @return static
     */
    public static function create(JMSSerializer $serializer = null): self
    {
        $serializer || $serializer = SerializerBuilder::create()
            ->configureHandlers(
                function (HandlerRegistry $registry) {
                    $registry->registerSubscribingHandler(
                        new MixedTypeHandler()
                    );
                }
            )
            ->addDefaultHandlers()
            ->build();

        return new static($serializer);
    }

    /**
     * @param object $requestModel
     * @return string
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
     * @param object $query
     * @return array
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
    ): BaseResponse {
        $body = (string)$response->getBody();
        $responseModel->setStatusCode($response->getStatusCode());

        if (empty($body)) {
            return $this->processEmptyResponse($responseModel, (int)$response->getHeaderLine('X-Total-Count'));
        } elseif ($response->getStatusCode() >= static::FAILED_STATUS_ENTRY_POINT) {
            return $this->processFailedResponse($responseModel, $body);
        } elseif ($responseModel instanceof CollectionResponse) {
            return $this->processCollectionResponse(
                $responseModel,
                $resourceClassName,
                $body,
                (int)$response->getHeaderLine('X-Total-Count')
            );
        } elseif ($responseModel instanceof ResourceResponse) {
            return $this->processResourceResponse($responseModel, $resourceClassName, $body);
        }

        throw new SerializerException('Unable deserialize response: Unexpected behavior');
    }

    /**
     * @param BaseResponse $responseModel
     * @param int $totalCount
     * @return EmptyResponse|CollectionResponse
     * @throws SerializerException
     */
    protected function processEmptyResponse(BaseResponse $responseModel, int $totalCount = 0): BaseResponse
    {
        if ($responseModel instanceof EmptyResponse) {
            return $responseModel;
        }
        if ($responseModel instanceof CollectionResponse) {
            $responseModel->setElements(new ArrayCollection());
            $responseModel->setTotalCount($totalCount);

            return $responseModel;
        }

        throw new SerializerException('Invalid empty response');
    }

    /**
     * @param BaseResponse $responseModel
     * @param string $body
     * @return BaseResponse
     * @throws SerializerException
     */
    protected function processFailedResponse(BaseResponse $responseModel, string $body): BaseResponse
    {
        try {
            $result = $this->serializer->deserialize(
                $body,
                'ArrayCollection<LevelCredit\LevelCreditApi\Model\Response\Error>',
                'json'
            );
        } catch (\Throwable $e) {
            try {
                $result = $this->serializer->deserialize(
                    $body,
                    'LevelCredit\LevelCreditApi\Model\Response\Error',
                    'json'
                );
            } catch (\Throwable $e) {
                $responseModel->setErrors(new ErrorCollection([new Error($body)]));

                return $responseModel;
            }
        }

        if ($result instanceof ArrayCollection) {
            $result = $result->toArray();
        }

        $responseModel->setErrors(new ErrorCollection(is_array($result) ? $result : [$result]));

        return $responseModel;
    }

    /**
     * @param CollectionResponse $responseModel
     * @param string $resourceClassName
     * @param string $body
     * @param int $totalCount
     * @return CollectionResponse
     * @throws SerializerException
     */
    protected function processCollectionResponse(
        CollectionResponse $responseModel,
        string $resourceClassName,
        string  $body,
        int $totalCount
    ): CollectionResponse {
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

        if ($totalCount > 0) {
            $responseModel->setTotalCount($totalCount);
        } else {
            $responseModel->setTotalCount($result->count());
        }

        return $responseModel;
    }

    /**
     * @param ResourceResponse $responseModel
     * @param string $resourceClassName
     * @param string $body
     * @return ResourceResponse
     * @throws SerializerException
     */
    protected function processResourceResponse(
        ResourceResponse $responseModel,
        string $resourceClassName,
        string  $body
    ): ResourceResponse {
        try {
            $resourceModel = $this->serializer->deserialize($body, $resourceClassName, 'json');
        } catch (\Throwable $e) {
            throw new SerializerException('Unable deserialize resource response: ' . $e->getMessage());
        }

        if (!is_a($resourceModel, $resourceClassName)) {
            throw new SerializerException('Unable deserialize resource response: Get incorrect result');
        }

        $responseModel->setResource($resourceModel);

        return $responseModel;
    }
}
