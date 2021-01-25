<?php

namespace LevelCredit\LevelCreditApi\Logging;

interface BodyFormatterInterface
{
    /**
     * @param string $body
     * @return string
     */
    public function format(string $body): string;
}
