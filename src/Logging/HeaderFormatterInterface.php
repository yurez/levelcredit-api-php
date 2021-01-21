<?php

namespace LevelCredit\LevelCreditApi\Logging;

interface HeaderFormatterInterface
{
    /**
     * @param string $name
     * @param array $values
     * @return array
     */
    public function format(string $name, array $values): array;
}
