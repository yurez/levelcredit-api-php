<?php

namespace LevelCredit\LevelCreditApi\LoggableBehavior;

use GuzzleHttp\MessageFormatter;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Psr\Log\NullLogger;

class DefaultLogHandler implements LogHandlerInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function create(LoggerInterface $logger): self
    {
        $logger || $logger = new NullLogger();

        return new static($logger);
    }

    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    public function getMessageFormatter(): MessageFormatter
    {
        return new MessageFormatter();
    }

    public function getLogLevel(): string
    {
        return LogLevel::ERROR;
    }
}
