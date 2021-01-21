<?php

namespace LevelCredit\LevelCreditApi\Logging;

use GuzzleHttp\MessageFormatter;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7 as Psr7;

/**
 * To be compatible with Guzzle 7.1.x we need to extend from GuzzleHttp\MessageFormatter
 *
 * Formats log messages using variable substitutions for requests, responses,
 * and other transactional data.
 *
 * The following variable substitutions are supported:
 *
 * - {request}:        Full HTTP request message
 * - {response}:       Full HTTP response message
 * - {ts}:             ISO 8601 date in GMT
 * - {date_iso_8601}   ISO 8601 date in GMT
 * - {date_common_log} Apache common log date using the configured timezone.
 * - {host}:           Host of the request
 * - {method}:         Method of the request
 * - {uri}:            URI of the request
 * - {version}:        Protocol version
 * - {target}:         Request target of the request (path + query + fragment)
 * - {hostname}:       Hostname of the machine that sent the request
 * - {code}:           Status code of the response (if available)
 * - {phrase}:         Reason phrase of the response  (if available)
 * - {error}:          Any error messages (if available)
 * - {req_header_*}:   Replace `*` with the lowercased name of a request header to add to the message
 * - {res_header_*}:   Replace `*` with the lowercased name of a response header to add to the message
 * - {req_headers}:    Request headers
 * - {res_headers}:    Response headers
 * - {req_body}:       Request body
 * - {res_body}:       Response body
 */
class ExtendedMessageFormatter extends MessageFormatter implements MessageFormatterInterface
{
    /**
     * Template used to format log messages
     * @var string
     */
    protected $template;

    /**
     * @var HeaderFormatterInterface
     */
    protected $headerFormatter;

    /**
     * @var BodyFormatterInterface
     */
    protected $bodyFormatter;

    /**
     * @var QueryFormatterInterface
     */
    protected $queryFormatter;

    /**
     * @param string $template Log message template
     */
    public function __construct($template = self::CLF)
    {
        $this->template = $template ?: self::CLF;

        parent::__construct($template);
    }

    /**
     * @param string $template
     * @return static
     */
    public static function create($template = self::CLF): self
    {
        return new static($template);
    }

    /**
     * @param HeaderFormatterInterface $headerFormatter
     * @return static
     */
    public function setHeaderFormatter(HeaderFormatterInterface $headerFormatter): self
    {
        $this->headerFormatter = $headerFormatter;

        return $this;
    }

    /**
     * @param BodyFormatterInterface $bodyFormatter
     * @return static
     */
    public function setBodyFormatter(BodyFormatterInterface $bodyFormatter): self
    {
        $this->bodyFormatter = $bodyFormatter;

        return $this;
    }

    /**
     * @param QueryFormatterInterface $queryFormatter
     * @return static
     */
    public function setQueryFormatter(QueryFormatterInterface $queryFormatter): self
    {
        $this->queryFormatter = $queryFormatter;

        return $this;
    }

    /**
     * Returns a formatted message string.
     *
     * @param RequestInterface $request Request that was sent
     * @param ResponseInterface|null $response Response that was received
     * @param \Throwable|null $error Exception that was received
     * @return string
     */
    public function format(
        RequestInterface $request,
        ?ResponseInterface $response = null,
        ?\Throwable $error = null
    ): string {
        $cache = [];

        /** @var string */
        return \preg_replace_callback(
            '/{\s*([A-Za-z_\-\.0-9]+)\s*}/',
            function (array $matches) use ($request, $response, $error, &$cache) {
                if (isset($cache[$matches[1]])) {
                    return $cache[$matches[1]];
                }

                $result = '';
                switch ($matches[1]) {
                    case 'request':
                        $result = Psr7\Message::toString($this->formatRequest($request));
                        break;
                    case 'response':
                        $result = $response ? Psr7\Message::toString($this->formatResponse($response)) : '';
                        break;
                    case 'req_headers':
                        $request = $this->formatRequestQuery($request);
                        $result = \trim($request->getMethod()
                                . ' ' . $request->getRequestTarget())
                            . ' HTTP/' . $request->getProtocolVersion() . "\r\n"
                            . $this->headers($request);
                        break;
                    case 'res_headers':
                        $result = $response ?
                            \sprintf(
                                'HTTP/%s %d %s',
                                $response->getProtocolVersion(),
                                $response->getStatusCode(),
                                $response->getReasonPhrase()
                            ) . "\r\n" . $this->headers($response)
                            : 'NULL';
                        break;
                    case 'req_body':
                        $result = $this->formatBody($request->getBody()->__toString());
                        break;
                    case 'res_body':
                        if (!$response instanceof ResponseInterface) {
                            $result = 'NULL';
                            break;
                        }

                        $body = $response->getBody();

                        if (!$body->isSeekable()) {
                            $result = 'RESPONSE_NOT_LOGGEABLE';
                            break;
                        }

                        $result = $this->formatBody($response->getBody()->__toString());
                        break;
                    case 'ts':
                    case 'date_iso_8601':
                        $result = \gmdate('c');
                        break;
                    case 'date_common_log':
                        $result = \date('d/M/Y:H:i:s O');
                        break;
                    case 'method':
                        $result = $request->getMethod();
                        break;
                    case 'version':
                        $result = $request->getProtocolVersion();
                        break;
                    case 'uri':
                    case 'url':
                        $result = $this->formatRequestQuery($request)->getUri();
                        break;
                    case 'target':
                        $result = $this->formatRequestQuery($request)->getRequestTarget();
                        break;
                    case 'req_version':
                        $result = $request->getProtocolVersion();
                        break;
                    case 'res_version':
                        $result = $response
                            ? $response->getProtocolVersion()
                            : 'NULL';
                        break;
                    case 'host':
                        $result = $request->getHeaderLine('Host');
                        break;
                    case 'hostname':
                        $result = \gethostname();
                        break;
                    case 'code':
                        $result = $response ? $response->getStatusCode() : 'NULL';
                        break;
                    case 'phrase':
                        $result = $response ? $response->getReasonPhrase() : 'NULL';
                        break;
                    case 'error':
                        $result = $error ? $error->getMessage() : 'NULL';
                        break;
                    default:
                        // handle prefixed dynamic headers
                        if (\strpos($matches[1], 'req_header_') === 0) {
                            $headerName = \substr($matches[1], 11);
                            $result = $this->formatMessageHeader($request, $headerName)->getHeaderLine($headerName);
                        } elseif (\strpos($matches[1], 'res_header_') === 0) {
                            $headerName = \substr($matches[1], 11);
                            $result = $response
                                ? $this->formatMessageHeader($response, $headerName)->getHeaderLine($headerName)
                                : 'NULL';
                        }
                }
                $cache[$matches[1]] = $result;

                return $result;
            },
            $this->template
        );
    }

    /**
     * Get headers from message as string
     * @param MessageInterface $message
     * @return string
     */
    protected function headers(MessageInterface $message): string
    {
        $this->formatMessageHeaders($message);
        $result = '';
        foreach ($message->getHeaders() as $name => $values) {
            $result .= $name . ': ' . \implode(', ', $values) . "\r\n";
        }

        return \trim($result);
    }

    /**
     * @param RequestInterface $request
     * @return RequestInterface|MessageInterface
     */
    protected function formatRequest(RequestInterface $request): RequestInterface
    {
        $request = $this->formatMessage($request);
        $request = $this->formatRequestQuery($request);

        return $request;
    }

    /**
     * @param ResponseInterface $response
     * @return ResponseInterface|MessageInterface
     */
    protected function formatResponse(ResponseInterface $response): ResponseInterface
    {
        return $this->formatMessage($response);
    }

    /**
     * @param MessageInterface $message
     * @return MessageInterface
     */
    protected function formatMessage(MessageInterface $message): MessageInterface
    {
        $message = $this->formatMessageHeaders($message);
        $message = $this->formatMessageBody($message);

        return $message;
    }

    /**
     * @param MessageInterface $message
     * @return MessageInterface
     */
    protected function formatMessageHeaders(MessageInterface $message): MessageInterface
    {
        if ($this->headerFormatter) {
            foreach ($message->getHeaders() as $name => $values) {
                $message = $this->formatMessageHeader($message, $name);
            }
        }

        return $message;
    }

    /**
     * @param MessageInterface $message
     * @param string $name
     * @return MessageInterface
     */
    protected function formatMessageHeader(MessageInterface $message, string $name): MessageInterface
    {
        if ($this->headerFormatter && $values = $message->getHeader($name)) {
            $values = $this->headerFormatter->format($name, $values);
            $message = $message->withHeader($name, $values);
        }

        return $message;
    }

    /**
     * @param MessageInterface $message
     * @return MessageInterface
     */
    protected function formatMessageBody(MessageInterface $message): MessageInterface
    {
        if ($this->bodyFormatter) {
            $body = $this->formatBody($message->getBody()->__toString());
            $message = $message->withBody(Psr7\Utils::streamFor($body));
        }

        return $message;
    }

    /**
     * @param string $body
     * @return string
     */
    protected function formatBody(string $body): string
    {
        if ($this->bodyFormatter && $body) {
            return $this->bodyFormatter->format($body);
        }

        return $body;
    }

    /**
     * @param RequestInterface $request
     * @return RequestInterface
     */
    protected function formatRequestQuery(RequestInterface $request): RequestInterface
    {
        if ($this->queryFormatter && $query = $request->getUri()->getQuery()) {
            $request->getUri()->withQuery($this->queryFormatter->format($query));
        }

        return $request;
    }
}
