<?php

namespace LevelCredit\LevelCreditApi\Logging;

interface QueryFormatterInterface
{
    /**
     * @param string $queryString
     * @return string
     */
    public function format(string $queryString): string;
}
