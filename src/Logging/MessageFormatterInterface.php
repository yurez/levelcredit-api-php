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
