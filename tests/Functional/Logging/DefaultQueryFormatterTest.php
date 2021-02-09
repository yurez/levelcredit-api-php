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

use LevelCredit\LevelCreditApi\Logging\DefaultQueryFormatter;
use PHPUnit\Framework\TestCase;

class DefaultQueryFormatterTest extends TestCase
{
    /**
     * @return array
     */
    public function queryDataProvider(): array
    {
        return [
            [
                'test=test1223',
                'test=test1223'
            ],
            [
                'test-Routing=12332323',
                'test-Routing=XXXXXXXXXXXXXXX',
            ],
            [
                'ssn=133232324332&cvv[]=121323&cvv[]=4343434&username[test]=jjjj',
                'ssn=XXX-XX-XXXX&cvv%5B%5D=XXX&cvv%5B%5D=XXX&username%5Btest%5D=jjjj',
            ],
            [
                'password=4343434efsdfsdfs^^^2&street-address-1=121%20Test+Street&CVV%5B0%5D=122&cvvt=test',
                'password=XXXXXXXXXXXXXXX&street-address-1=XXXXXXXXXXXXXXX&CVV%5B%5D=XXX&cvvt=test',
            ],
            [
                'birthdate=1986-22-22&test[account]=ewrere',
                'birthdate=XXXX-XX-XX&test%5Baccount%5D=XXXXXXXXXXXXXXX',
            ],
        ];
    }

    /**
     * @param string $queryString
     * @param string $result
     * @test
     * @dataProvider queryDataProvider
     */
    public function shouldFormatQuery(string $queryString, string $result): void
    {
        $formatter = DefaultQueryFormatter::create([
            '/^ssn$/i' => 'XXX-XX-XXXX',
            '/birthdate/i' => 'XXXX-XX-XX',
            '/account/i' => 'XXXXXXXXXXXXXXX',
            '/^cvv$/i' => 'XXX',
            '/routing/i' => 'XXXXXXXXXXXXXXX',
            '/street/i' => 'XXXXXXXXXXXXXXX',
            '/address/i' => 'XXXXXXXXXXXXXXX',
            '/password/i' => 'XXXXXXXXXXXXXXX',
        ])->addReplacement('/username/i', 'username');

        $this->assertEquals($result, $formatter->format($queryString));
    }
}
