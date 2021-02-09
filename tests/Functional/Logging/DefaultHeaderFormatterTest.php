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

use LevelCredit\LevelCreditApi\Logging\DefaultHeaderFormatter;
use PHPUnit\Framework\TestCase;

class DefaultHeaderFormatterTest extends TestCase
{
    /**
     * @test
     */
    public function shouldFormatHeader(): void
    {
        $formatter = DefaultHeaderFormatter::create([
            '/authorization/i' => '[REMOVED]',
        ]);

        $this->assertEquals(['[REMOVED]'], $formatter->format('Authorization', ['Token', 'secret']));
    }
}
