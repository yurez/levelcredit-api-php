<?php

namespace LevelCredit\LevelCreditApi\Logging;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * To be compatible with Guzzle 7.1.x
 */
if (interface_exists('\GuzzleHttp\MessageFormatterInterface')) {
    interface MessageFormatterInterface extends \GuzzleHttp\MessageFormatterInterface
    {
    }
} else {
    interface MessageFormatterInterface
    {
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
        ): string;
    }
}
