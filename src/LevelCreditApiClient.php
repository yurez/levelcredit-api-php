<?php

namespace LevelCredit\LevelCreditApi;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use LevelCredit\LevelCreditApi\LoggableBehavior\LogHandlerInterface;
use LevelCredit\LevelCreditApi\Model\Request\CreateTradelineSyncRequest;
use LevelCredit\LevelCreditApi\Model\Request\GetPartnerUsersFilter;
use LevelCredit\LevelCreditApi\Model\Request\PatchTradelineSyncRequest;
use LevelCredit\LevelCreditApi\Model\Response\AccessTokenResponse;
use LevelCredit\LevelCreditApi\Model\Response\BaseResponse;
use LevelCredit\LevelCreditApi\Model\Response\EmptyResponse;
use LevelCredit\LevelCreditApi\Model\Response\Resource\AccessToken;
use LevelCredit\LevelCreditApi\Model\Response\Resource\Sync;
use LevelCredit\LevelCreditApi\Model\Response\Resource\User;
use LevelCredit\LevelCreditApi\Model\Response\SyncResourceResponse;
use LevelCredit\LevelCreditApi\Model\Response\UserCollectionResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class LevelCreditApiClient
{
    protected const BASE_URI = 'https://my.levelcredit.com';

    protected const BASE_API_PREFIX = '/api';

    protected const API_PATHS = [
        'auth' => '/oauth/v2/token',
        'createTradelineSync' => '/tradeline/syncs',
        'addDataToTradelineSync' => '/tradeline/syncs/%s/data',
        'patchTradelineSync' => '/tradeline/syncs',
        'getPartnerUsers' => '/partner/users',
    ];

    protected const RESPONSE_MAP = [
        'getAccessTokenByUsernamePassword' => AccessToken::class,
        'getAccessTokenByRefreshToken' => AccessToken::class,
        'createTradelineSync' => Sync::class,
        'getPartnerUsers' => User::class,
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

    public function __construct(
        Serializer $serializer,
        string $clientId = '',
        string $clientSecret = '',
        string $baseUri = self::BASE_URI,
    ) {
        $this->serializer = $serializer;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->baseUri = $baseUri;
        $this->handlerStack = HandlerStack::create();
    }

    public static function create(
        string $clientId = '',
        string $clientSecret = '',
        string $baseUri = self::BASE_URI
    ): self {
        return new static(Serializer::create(), $clientId, $clientSecret, $baseUri);
    }

    /**
     * @param LogHandlerInterface $logHandler
     */
    public function addLogHandler(LogHandlerInterface $logHandler)
    {
        $this->addRewindMapResponse(self::LOG_STACK);
        $this->handlerStack->push(
            Middleware::log($logHandler->getLogger(), $logHandler->getMessageFormatter(), $logHandler->getLogLevel()),
            self::LOG_STACK
        );
    }

    public function disableLogHandlers()
    {
        $this->handlerStack->remove(self::LOG_STACK);
    }

    public function setAccessToken(string $accessToken): self
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
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
     * @throws Exception\ClientException
     */
    protected function sendAuthRequest(string $grandType, array $credentials): ResponseInterface
    {
        return $this->__sendRequest(
            'GET',
            static::API_PATHS['auth'],
            [
                RequestOptions::QUERY => array_merge(
                    [
                        'client_id' => $this->clientId,
                        'client_secret' => $this->clientSecret,
                        'grant_type' => $grandType,
                    ],
                    $credentials
                ),
            ]
        );
    }

    /**
     * @param string $method HTTP method
     * @param string|UriInterface $path
     * @param array $options request options
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

    protected function prepareClient(): Client
    {
        return new Client(['handler' => $this->handlerStack, 'base_uri' => $this->baseUri]);
    }

    /**
     * On add middleware that should work with response body please BEFORE use this for rewind body response
     */
    protected function addRewindMapResponse(string $handlerName): void
    {
        $mapResponse = Middleware::mapResponse(
            function(ResponseInterface $response) {
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
     * @throws Exception\ClientException
     */
    protected function preparePath(string $method, ...$ids): string
    {
        if (!isset(static::API_PATHS[$method])) {
            throw new Exception\ClientException(sprintf('Invalid path for "%s"', $method));
        }

        return vsprintf(self::BASE_API_PREFIX . static::API_PATHS[$method], $ids);
    }

    protected function prepareAuthorizeHeader(string $accessToken = null): array
    {
        return $accessToken ? ['Authorization' => 'Bearer ' . $accessToken] : [];
    }

    /**
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