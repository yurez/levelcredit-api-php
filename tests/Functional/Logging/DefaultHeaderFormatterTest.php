<?php

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
