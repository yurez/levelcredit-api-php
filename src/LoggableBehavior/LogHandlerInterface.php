<?php

namespace LevelCredit\LevelCreditApi\LoggableBehavior;

use GuzzleHttp\MessageFormatter;
use Psr\Log\LoggerInterface;

interface LogHandlerInterface
{
    public function getLogger(): LoggerInterface;

    public function getMessageFormatter(): MessageFormatter;

    /**
     * @see \Psr\Log\LogLevel
     */
    public function getLogLevel(): string;
}
