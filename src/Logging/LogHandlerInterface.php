<?php

namespace LevelCredit\LevelCreditApi\Logging;

use Psr\Log\LoggerInterface;

interface LogHandlerInterface
{
    public function getLogger(): LoggerInterface;

    public function getMessageFormatter(): MessageFormatterInterface;

    /**
     * @see \Psr\Log\LogLevel
     */
    public function getLogLevel(): string;
}
