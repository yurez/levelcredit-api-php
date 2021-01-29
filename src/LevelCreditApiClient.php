<?php

namespace LevelCredit\LevelCreditApi;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use LevelCredit\LevelCreditApi\Logging\LogHandlerInterface;
use LevelCredit\LevelCreditApi\Model\Request\CreateTradelineSyncRequest;
use LevelCredit\LevelCreditApi\Model\Request\GetPartnerUsersFilter;
use LevelCredit\LevelCreditApi\Model\Request\PatchTradelineSyncRequest;
use LevelCredit\LevelCreditApi\Model\Request\PayProductRequest;
use LevelCredit\LevelCreditApi\Model\Response\AccessTokenResponse;
use LevelCredit\LevelCreditApi\Model\Response\BaseResponse;
use LevelCredit\LevelCreditApi\Model\Response\EmptyResponse;
use LevelCredit\LevelCreditApi\Model\Response\OrderResourceResponse;
use LevelCredit\LevelCreditApi\Model\Response\Resource\AccessToken;
use LevelCredit\LevelCreditApi\Model\Response\Resource\Order;
use LevelCredit\LevelCreditApi\Model\Response\Resource\Sync;
use LevelCredit\LevelCreditApi\Model\Response\Resource\User;
use LevelCredit\LevelCreditApi\Model\Response\SyncResourceResponse;
use LevelCredit\LevelCreditApi\Model\Response\UserCollectionResponse;
use LevelCredit\LevelCreditApi\Serializer\Serializer;
use LevelCredit\LevelCreditApi\Serializer\SerializerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class LevelCreditApiClient
{
    protected const BASE_URI = 'https://my.sandbox2.renttrack.com';

    protected const BASE_API_PREFIX = '/api';

    protected const API_PATHS = [
        'auth' => '/oauth/v2/token',
        'createTradelineSync' => '/tradeline/syncs',
        'addDataToTradelineSync' => '/tradeline/syncs/%s/data',
        'patchTradelineSync' => '/tradeline/syncs',
        'getPartnerUsers' => '/partner/users',
        'payProduct' => '/products/%s/orders',
    ];

    protected const RESPONSE_MAP = [
        'getAccessTokenByUsernamePassword' => AccessToken::class,
        'getAccessTokenByRefreshToken' => AccessToken::class,
        'createTradelineSync' => Sync::class,
        'getPartnerUsers' => User::class,
        'payProduct' => Order::class,
    ];

    protected const LOG_STACK = 'logging';

    /**
     * @var HandlerStack
     */
    protected $handlerStack;

    /**
     * @var string
     */
    protected $baseUri;

    /**
     * @var string
     */
    protected $clientId;

    /**
     * @var string
     */
    protected $clientSecret;

    /**
     * @var string
     */
    protected $accessToken;

    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * @param string $clientId
     * @param string $clientSecret
     * @param string $baseUri
     */
    public function __construct(
        string $clientId = '',
        string $clientSecret = '',
        string $baseUri = self::BASE_URI
    ) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->baseUri = $baseUri;
        $this->handlerStack = HandlerStack::create();
        $this->serializer = Serializer::create();
    }

    /**
     * @param string $clientId
     * @param string $clientSecret
     * @return static
     */
    public static function create(string $clientId = '', string $clientSecret = ''): self
    {
        return new static($clientId, $clientSecret);
    }

    /**
     * @param SerializerInterface $serializer
     * @return static
     */
    public function setSerializer(SerializerInterface $serializer): self
    {
        $this->serializer = $serializer;

        return $this;
    }

    /**
     * @param string $clientId
     * @return static
     */
    public function setClientId(string $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * @param string $clientSecret
     * @return static
     */
    public function setClientSecret(string $clientSecret): self
    {
        $this->clientSecret = $clientSecret;

        return $this;
    }

    /**
     * @param string $baseUri
     * @return static
     */
    public function setBaseUri(string $baseUri): self
    {
        $this->baseUri = $baseUri;

        return $this;
    }

    /**
     * @param LogHandlerInterface $logHandler
     * @return static
     */
    public function addLogHandler(LogHandlerInterface $logHandler): self
    {
        $this->addRewindMapResponse(self::LOG_STACK);
        $this->handlerStack->push(
            Middleware::log($logHandler->getLogger(), $logHandler->getMessageFormatter(), $logHandler->getLogLevel()),
            self::LOG_STACK
        );

        return $this;
    }

    /**
     * @return static
     */
    public function disableLogHandlers(): self
    {
        $this->handlerStack->remove(self::LOG_STACK);

        return $this;
    }

    /**
     * @param string $accessToken
     * @return static
     */
    public function setAccessToken(string $accessToken): self
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * @param string $username
     * @param string $password
     * @return AccessTokenResponse|BaseResponse
     * @throws Exception\LevelCreditApiException
     */
    public function getAccessTokenByUsernamePassword(string $username, string $password): AccessTokenResponse
    {
        $response = $this->sendAuthRequest(
            'password',
            ['username' => $username, 'password' => $password]
        );

        return $this->parseResponse(__FUNCTION__, $response, new AccessTokenResponse());
    }

    /**
     * @param string $refreshToken
     * @return AccessTokenResponse|BaseResponse
     * @throws Exception\LevelCreditApiException
     */
    public function getAccessTokenByRefreshToken(string $refreshToken): AccessTokenResponse
    {
        $response = $this->sendAuthRequest(
            'refresh_token',
            ['refresh_token' => $refreshToken]
        );

        return $this->parseResponse(__FUNCTION__, $response, new AccessTokenResponse());
    }

    /**
     * @param CreateTradelineSyncRequest $request
     * @param string|null $accessToken
     * @return SyncResourceResponse|BaseResponse
     * @throws Exception\LevelCreditApiException
     */
    public function createTradelineSync(
        CreateTradelineSyncRequest $request,
        string $accessToken = null
    ): SyncResourceResponse {
        $response = $this->__sendRequest(
            'POST',
            $this->preparePath(__FUNCTION__),
            [],
            $this->prepareAuthorizeHeader($accessToken),
            $this->prepareBodyRequest($request)
        );

        return $this->parseResponse(__FUNCTION__, $response, new SyncResourceResponse());
    }

    /**
     * @param int $syncId
     * @param string $data
     * @param string|null $accessToken
     * @return EmptyResponse|BaseResponse
     * @throws Exception\LevelCreditApiException
     */
    public function addDataToTradelineSync(
        int $syncId,
        string $data,
        string $accessToken = null
    ): EmptyResponse {
        $response = $this->__sendRequest(
            'POST',
            $this->preparePath(__FUNCTION__, $syncId),
            [],
            array_merge(['Content-Type' => 'plain/text'], $this->prepareAuthorizeHeader($accessToken)),
            $data
        );

        return $this->parseResponse(__FUNCTION__, $response, new EmptyResponse());
    }

    /**
     * @param int $syncId
     * @param PatchTradelineSyncRequest $request
     * @param string|null $accessToken
     * @return EmptyResponse|BaseResponse
     * @throws Exception\LevelCreditApiException
     */
    public function patchTradelineSync(
        int $syncId,
        PatchTradelineSyncRequest $request,
        string $accessToken = null
    ): EmptyResponse {
        $response = $this->__sendRequest(
            'PATCH',
            $this->preparePath(__FUNCTION__, $syncId),
            [],
            $this->prepareAuthorizeHeader($accessToken),
            $this->prepareBodyRequest($request)
        );

        return $this->parseResponse(__FUNCTION__, $response, new EmptyResponse());
    }

    /**
     * @param GetPartnerUsersFilter $filter
     * @param string|null $accessToken
     * @return UserCollectionResponse|BaseResponse
     * @throws Exception\LevelCreditApiException
     */
    public function getPartnerUsers(GetPartnerUsersFilter $filter, string $accessToken = null): UserCollectionResponse
    {
        $response = $this->__sendRequest(
            'GET',
            $this->preparePath(__FUNCTION__),
            [
                RequestOptions::QUERY => $this->prepareQueryRequest($filter)
            ],
            $this->prepareAuthorizeHeader($accessToken)
        );

        return $this->parseResponse(__FUNCTION__, $response, new UserCollectionResponse());
    }

    /**
     * @param string $productCode
     * @param PayProductRequest $request
     * @param string|null $accessToken
     * @return OrderResourceResponse|BaseResponse
     * @throws Exception\LevelCreditApiException
     */
    public function payProduct(
        string $productCode,
        PayProductRequest $request,
        string $accessToken = null
    ): OrderResourceResponse {
        $response = $this->__sendRequest(
            'POST',
            $this->preparePath(__FUNCTION__, $productCode),
            [],
            $this->prepareAuthorizeHeader($accessToken),
            $this->prepareBodyRequest($request)
        );

        return $this->parseResponse(__FUNCTION__, $response, new OrderResourceResponse());
    }

    /**
     * @param string $grantType
     * @param array $credentials
     * @return ResponseInterface
     * @throws Exception\ClientException
     */
    protected function sendAuthRequest(string $grantType, array $credentials): ResponseInterface
    {
        return $this->__sendRequest(
            'GET',
            static::API_PATHS['auth'],
            [
                RequestOptions::QUERY => array_merge(
                    [
                        'client_id' => $this->clientId,
                        'client_secret' => $this->clientSecret,
                        'grant_type' => $grantType,
                    ],
                    $credentials
                ),
            ]
        );
    }

    /**
     * @param string $method HTTP method
     * @param string|UriInterface $path
     * @param array $options Request options
     * @param array $headers Request headers
     * @param string|null|resource|StreamInterface $body Request body
     * @param bool $withDefaultHeaders
     * @return ResponseInterface
     * @see \GuzzleHttp\RequestOptions
     * @throws Exception\ClientException
     */
    protected function __sendRequest(
        string $method,
        string $path,
        array $options = [],
        array $headers = [],
        $body = null,
        $withDefaultHeaders = true
    ): ResponseInterface {
        $client = $this->prepareClient();
        !$withDefaultHeaders || $headers = array_merge(
            ['Content-Type' => 'application/json'],
            $this->prepareAuthorizeHeader($this->accessToken),
            $headers
        );
        $request = new Request($method, $path, $headers, $body);

        try {
            return $client->send($request, $options);
        } catch (GuzzleException $e) {
            throw new Exception\ClientException($e->getMessage());
        }
    }

    /**
     * @return Client
     */
    protected function prepareClient(): Client
    {
        return new Client(['handler' => $this->handlerStack, 'base_uri' => $this->baseUri, 'http_errors' => false]);
    }

    /**
     * On add middleware that should work with response body please BEFORE use this for rewind body response
     * @param string $handlerName
     */
    protected function addRewindMapResponse(string $handlerName): void
    {
        $mapResponse = Middleware::mapResponse(
            function (ResponseInterface $response) {
                $response->getBody()->rewind();

                return $response;
            }
        );
        $this->handlerStack->push($mapResponse, $handlerName);
    }

    /**
     * @param mixed $request
     * @return mixed
     * @throws Exception\SerializerException
     */
    protected function prepareBodyRequest($request)
    {
        return is_object($request) ? $this->serializer->serializeRequest($request) : $request;
    }

    /**
     * @param mixed $query
     * @return mixed
     * @throws Exception\SerializerException
     */
    protected function prepareQueryRequest($query)
    {
        return is_object($query) ? $this->serializer->serializeQuery($query) : $query;
    }

    /**
     * @param string $method
     * @param mixed ...$ids
     * @return string
     * @throws Exception\ClientException
     */
    protected function preparePath(string $method, ...$ids): string
    {
        if (!isset(static::API_PATHS[$method])) {
            throw new Exception\ClientException(sprintf('Invalid path for "%s"', $method));
        }

        return vsprintf(self::BASE_API_PREFIX . static::API_PATHS[$method], $ids);
    }

    /**
     * @param string|null $accessToken
     * @return array|string[]
     */
    protected function prepareAuthorizeHeader(string $accessToken = null): array
    {
        return $accessToken ? ['Authorization' => 'Bearer ' . $accessToken] : [];
    }

    /**
     * @param string $method
     * @param ResponseInterface $response
     * @param BaseResponse $responseModel
     * @return BaseResponse
     * @throws Exception\SerializerException
     */
    protected function parseResponse(
        string $method,
        ResponseInterface $response,
        BaseResponse $responseModel
    ): BaseResponse {
        return $this->serializer->deserializeResponse(
            $response,
            $responseModel,
            static::RESPONSE_MAP[$method] ?? null
        );
    }
}
