<?php

namespace LevelCredit\LevelCreditApi\Logging;

use GuzzleHttp\MessageFormatter;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\Message;

class ExtendedMessageFormatter extends MessageFormatter implements MessageFormatterInterface
{
    /**
     * Template used to format log messages
     * @var string
     */
    protected $template;

    /**
     * @param string $template Log message template
     */
    public function __construct($template = self::CLF)
    {
        $this->template = $template ?: self::CLF;
    }

    /**
     * Returns a formatted message string.
     *
     * @param RequestInterface $request Request that was sent
     * @param ResponseInterface|null $response Response that was received
     * @param \Throwable|null $error Exception that was received
     *
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
                        $result = Message::toString($request);
                        break;
                    case 'response':
                        $result = $response ? Message::toString($response) : '';
                        break;
                    case 'req_headers':
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
                        $result = $request->getBody()->__toString();
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

                        $result = $response->getBody()->__toString();
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
                        $result = $request->getUri();
                        break;
                    case 'target':
                        $result = $request->getRequestTarget();
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
                            $result = $request->getHeaderLine(\substr($matches[1], 11));
                        } elseif (\strpos($matches[1], 'res_header_') === 0) {
                            $result = $response
                                ? $response->getHeaderLine(\substr($matches[1], 11))
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
        $result = '';
        foreach ($message->getHeaders() as $name => $values) {
            $result .= $name . ': ' . \implode(', ', $values) . "\r\n";
        }

        return \trim($result);
    }

    /**
     * @param RequestInterface $request
     * @return RequestInterface
     */
    protected function formatRequest(RequestInterface $request)
    {
        return $this->formatMessage($request, $this->headerFormatter, $this->requestBodyFormatter);
    }

    /**
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    protected function formatResponse(ResponseInterface $response)
    {
        return $this->formatMessage($response, $this->headerFormatter, $this->responseBodyFormatter);
    }

    /**
     * @param MessageInterface $message
     * @param HeaderFormatterInterface|null $headerFormatter
     * @param BodyFormatterInterface|null $bodyFormatter
     *
     * @return MessageInterface
     */
    protected function formatMessage(
        MessageInterface $message,
        HeaderFormatterInterface $headerFormatter = null,
        BodyFormatterInterface $bodyFormatter = null
    ) {

        if ($bodyFormatter) {
            $body = $bodyFormatter->format((string) $message->getBody());

            $message = $message->withBody(Psr7\S($body));
        }

        return $message;
    }

    protected function formatHeader(HeaderFormatterInterface $formatter, MessageInterface $message)
    {
        if ($formatter) {
            foreach ($message->getHeaders() as $name => $values) {
                $value = $headerFormatter->format($name, $values);

                $message = $message->withHeader($name, $value);
            }
        }

        return $message;
    }
}
