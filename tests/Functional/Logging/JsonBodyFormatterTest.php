<?php

namespace LevelCredit\LevelCreditApi\Tests\Functional\Logging;

use LevelCredit\LevelCreditApi\Logging\JsonBodyFormatter;
use PHPUnit\Framework\TestCase;

class JsonBodyFormatterTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnEmptyStringIfBodyIsEmpty(): void
    {
        $formatter = JsonBodyFormatter::create([
            '/pass/i' => '[PASS-REMOVED]',
            '/user/i' => '[USER-REMOVED]',
        ]);

        $this->assertEquals('', $formatter->format(''));
    }

    /**
     * @test
     */
    public function shouldReturnOriginalBodyIfDecodedStringIsNotArray(): void
    {
        $formatter = JsonBodyFormatter::create([
            '/pass/i' => '[PASS-REMOVED]',
            '/user/i' => '[USER-REMOVED]',
        ]);

        $this->assertEquals('"string"', $formatter->format('"string"'));
    }

    /**
     * @test
     */
    public function shouldReplaceIfNameMatch(): void
    {
        $formatter = JsonBodyFormatter::create([
            '/pass/i' => '[PASS-REMOVED]',
            '/user/i' => '[USER-REMOVED]',
        ]);

        $data = [
            'key1' => 'value1',
            'password' => 'secret',
            'child' => [
                'dduserkk' => 'secret',
                'key2' => 'value2',
            ]
        ];

        $expected = [
            'key1' => 'value1',
            'password' => '[PASS-REMOVED]',
            'child' => [
                'dduserkk' => '[USER-REMOVED]',
                'key2' => 'value2',
            ]
        ];

        $this->assertEquals(json_encode($expected), $formatter->format(json_encode($data)));
    }
}
