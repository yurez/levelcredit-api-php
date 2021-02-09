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

namespace LevelCredit\LevelCreditApi\Tests\Functional\Logging;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use LevelCredit\LevelCreditApi\Logging\DefaultHeaderFormatter;
use LevelCredit\LevelCreditApi\Logging\DefaultQueryFormatter;
use LevelCredit\LevelCreditApi\Logging\ExtendedMessageFormatter;
use LevelCredit\LevelCreditApi\Logging\JsonBodyFormatter;
use PHPUnit\Framework\TestCase;

class ExtendedMessageFormatterTest extends TestCase
{
    /**
     * @test
     */
    public function shouldFormatRequestResponse(): void
    {
        $messageFormatter = ExtendedMessageFormatter::create(
            ">>>>>>>>\n{request}\n<<<<<<<<\n{response}\n--------\n{error}"
        );

        $request = new Request(
            'POST',
            'https://my.levelcredit.com/api/test?password=some_password&test=1&embeds[]=subscriptions',
            ['Authorization' => 'Bearer ZjYwZDEzOTgzMjJmYjc4NWRlYjdkOWZ', 'Content-Type' => 'application/json'],
            '{"ssn":"123-33-3434","address":{"street1":"123 Test Street","city":"New-York"}}'
        );
        $response = new Response(
            201,
            ['X-Total-Count' => 2],
            '{"access_token":"ZjYwZDEzOT","expires_in":3600,"refresh_token":"MGMxODdmZjkxZ"}'
        );

        $result = $messageFormatter->format($request, $response);

        $expectedResult = <<<RESULT
>>>>>>>>
POST /api/test?password=some_password&test=1&embeds%5B%5D=subscriptions HTTP/1.1\r
Host: my.levelcredit.com\r
Authorization: Bearer ZjYwZDEzOTgzMjJmYjc4NWRlYjdkOWZ\r
Content-Type: application/json\r
\r
{"ssn":"123-33-3434","address":{"street1":"123 Test Street","city":"New-York"}}
<<<<<<<<
HTTP/1.1 201 Created\r
X-Total-Count: 2\r
\r
{"access_token":"ZjYwZDEzOT","expires_in":3600,"refresh_token":"MGMxODdmZjkxZ"}
--------
NULL
RESULT;

        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @test
     */
    public function shouldUseHeaderQueryAndBodyFormattersOnFormatRequestAndResponse(): void
    {
        $fullTemplate = ">>>>>>>>\n{request}\n<<<<<<<<\n{response}\n--------\n{error}";

        $messageFormatter = ExtendedMessageFormatter::create($fullTemplate)
            ->setQueryFormatter(DefaultQueryFormatter::create(['/password/i' => 'X-REMOVED-PASSWORD-X']))
            ->setHeaderFormatter(DefaultHeaderFormatter::create(['/authorization/i' => 'X-REMOVED-AUTHORIZATION-X']))
            ->setBodyFormatter(
                JsonBodyFormatter::create(
                    [
                        '/^ssn$/i' => 'X-REMOVED-SSN-X',
                        '/birthdate/i' => 'X-REMOVED-BIRTHDAY-X',
                        '/account/i' => 'X-REMOVED-ACCOUNT_NUMBER-X',
                        '/^cvv$/i' => 'X-REMOVED-CVV-X',
                        '/routing/i' => 'X-REMOVED-ROUTING-X',
                        '/street/i' => 'X-REMOVED-STREET-X',
                        '/address/i' => 'X-REMOVED-ADDRESS-X',
                        '/password/i' => 'X-REMOVED-PASSWORD-X',
                        '/access_token/i' => 'X-REMOVED-ACCESS_TOKEN-X',
                        '/refresh_token/i' => 'X-REMOVED-REFRESH_TOKEN-X',
                    ]
                )
            )
        ;

        $request = new Request(
            'POST',
            'https://my.levelcredit.com/api/test?password=somePass&test=1&embeds[]=subscriptions',
            ['Authorization' => 'Bearer ZjYwZDEzOTgzMjJmYjc4NWRlYjdkOWZ', 'Content-Type' => 'application/json'],
            '{"ssn":"123-33-3434","address":{"street1":"123 Test Street","city":"New-York"}}'
        );
        $response = new Response(
            201,
            ['X-Total-Count' => 2],
            '{"access_token":"ZjYwZDEzOT","expires_in":3600,"refresh_token":"MGMxODdmZjkxZ"}'
        );

        $result = $messageFormatter->format($request, $response);

        $expectedResult = <<<RESULT
>>>>>>>>
POST /api/test?password=X-REMOVED-PASSWORD-X&test=1&embeds%5B%5D=subscriptions HTTP/1.1\r
Host: my.levelcredit.com\r
Authorization: X-REMOVED-AUTHORIZATION-X\r
Content-Type: application/json\r
\r
{"ssn":"X-REMOVED-SSN-X","address":{"street1":"X-REMOVED-STREET-X","city":"New-York"}}
<<<<<<<<
HTTP/1.1 201 Created\r
X-Total-Count: 2\r
\r
{"access_token":"X-REMOVED-ACCESS_TOKEN-X","expires_in":3600,"refresh_token":"X-REMOVED-REFRESH_TOKEN-X"}
--------
NULL
RESULT;

        $this->assertEquals($expectedResult, $result);
    }
}
