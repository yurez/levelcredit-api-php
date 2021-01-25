<?php

namespace LevelCredit\LevelCreditApi\Logging;

use Psr\Log\LoggerInterface;

interface LogHandlerInterface
{
    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface;

    /**
     * @return MessageFormatterInterface
     */
    public function getMessageFormatter(): MessageFormatterInterface;

    /**
     * @see \Psr\Log\LogLevel
     * @return string
     */
    public function getLogLevel(): string;
}
