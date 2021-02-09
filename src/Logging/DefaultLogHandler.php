<?php

/* Copyright(c) 2021 by RentTrack, Inc.  All rights reserved.
 *
 * This software contains proprietary and confidential information of
 * RentTrack Inc., and its suppliers.  Except as may be set forth
 * in the license agreement under which this software is supplied, use,
 * disclosure, or  reproduction is prohibited without the prior express
 * written consent of RentTrack, Inc.
 *
 * The license terms of service are hosted at https://github.com/levelcredit/levelcredit-api-php/blob/master/LICENSE
 */

namespace LevelCredit\LevelCreditApi\Logging;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class DefaultLogHandler implements LogHandlerInterface
{
    private const FULL_LOG = "[{ts}]>>>>>>>>\n{request}\n<<<<<<<<\n{response}\n--------\n{error}";

    private const REQ_ONLY = "[{ts}]{hostname} - \"{method} {target} \" {req_headers} "
        . "\n{req_body} \n{code} {phrase} \n{error}";

    private const RES_ONLY = "[{ts}]{hostname} self- \"{method} {target} \" {code} {phrase} \n{res_headers} "
        . "\n{res_body} \n{error}";

    private const WOUT_ANY = "[{ts}]{hostname} - \"{method} {target} \" {code} {phrase} "
        . "{res_header_Content-Length} \n{error}";

    private const PRIVATE_HEADERS_REPLACEMENT = [
        '/authorization/i' => 'X-REMOVED-AUTHORIZATION-X',
    ];

    private const PRIVATE_FIELDS_REPLACEMENT = [
        '/^ssn$/i' => 'X-REMOVED-SSN-X',
        '/account/i' => 'X-REMOVED-ACCOUNT_NUMBER-X',
        '/^cvv$/i' => 'X-REMOVED-CVV-X',
        '/routing/i' => 'X-REMOVED-ROUTING-X',
        '/street/i' => 'X-REMOVED-STREET-X',
        '/address/i' => 'X-REMOVED-ADDRESS-X',
        '/password/i' => 'X-REMOVED-PASSWORD-X',
        '/access_token/i' => 'X-REMOVED-ACCESS_TOKEN-X',
        '/refresh_token/i' => 'X-REMOVED-REFRESH_TOKEN-X',
        '/client_secret/i' => 'X-REMOVED-CLIENT_SECRET-X',
    ];

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var bool
     */
    protected $logRequest;

    /**
     * @var bool
     */
    protected $logResponse;

    /**
     * @var string
     */
    protected $logLevel;

    /**
     * @var string[]
     */
    protected $headersReplacement = self::PRIVATE_HEADERS_REPLACEMENT;

    /**
     * @var string[]
     */
    protected $fieldsReplacement = self::PRIVATE_FIELDS_REPLACEMENT;

    /**
     * @param LoggerInterface $logger
     * @param bool $logRequest
     * @param bool $logResponse
     * @param string $logLevel
     */
    public function __construct(
        LoggerInterface $logger,
        $logRequest = true,
        $logResponse = true,
        $logLevel = LogLevel::DEBUG
    ) {
        $this->logger = $logger;
        $this->logRequest = $logRequest;
        $this->logResponse = $logResponse;
        $this->logLevel = $logLevel;
    }

    /**
     * @param string $logLevel
     * @param bool $logRequest
     * @param bool $logResponse
     * @param LoggerInterface|null $logger
     * @return static
     */
    public static function create(
        LoggerInterface $logger,
        $logLevel = LogLevel::DEBUG,
        $logRequest = true,
        $logResponse = true
    ): self {
        return new static($logger, $logRequest, $logResponse, $logLevel);
    }

    /**
     * @param string $logLevel
     * @return static
     */
    public function setLogLevel(string $logLevel): self
    {
        $this->logLevel = $logLevel;

        return $this;
    }

    /**
     * @param string $headerReplacement
     * @return static
     */
    public function addHeaderReplacement(string $headerReplacement): self
    {
        $this->headersReplacement[] = $headerReplacement;

        return $this;
    }

    /**
     * @param string $fieldReplacement
     * @return static
     */
    public function addFieldReplacement(string $fieldReplacement): self
    {
        $this->fieldsReplacement[] = $fieldReplacement;

        return $this;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @return MessageFormatterInterface
     */
    public function getMessageFormatter(): MessageFormatterInterface
    {
        return ExtendedMessageFormatter::create($this->getTemplate())
            ->setHeaderFormatter(DefaultHeaderFormatter::create($this->headersReplacement))
            ->setBodyFormatter(JsonBodyFormatter::create($this->fieldsReplacement))
            ->setQueryFormatter(DefaultQueryFormatter::create($this->fieldsReplacement));
    }

    /**
     * @return string
     */
    public function getLogLevel(): string
    {
        return $this->logLevel;
    }

    /**
     * @return string
     */
    protected function getTemplate(): string
    {
        if ($this->logRequest && $this->logResponse) {
            return self::FULL_LOG;
        } elseif ($this->logRequest) {
            return self::REQ_ONLY;
        } elseif ($this->logResponse) {
            return self::RES_ONLY;
        }

        return self::WOUT_ANY;
    }
}
