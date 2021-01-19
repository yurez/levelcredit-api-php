<?php

namespace LevelCredit\LevelCreditApi\Model\Request;

use LevelCredit\LevelCreditApi\Enum\TradelineSyncType;

class CreateTradelineSyncRequest
{
    /**
     * @var string
     */
    protected $summaryEmail;

    /**
     * @var int
     */
    protected $timeoutMinutes;

    /**
     * @var string
     * @see TradelineSyncType
     */
    protected $type;

    public function getSummaryEmail(): string
    {
        return $this->summaryEmail;
    }

    public function setSummaryEmail(string $summaryEmail): self
    {
        $this->summaryEmail = $summaryEmail;

        return $this;
    }

    public function getTimeoutMinutes(): int
    {
        return $this->timeoutMinutes;
    }

    public function setTimeoutMinutes(int $timeoutMinutes): self
    {
        $this->timeoutMinutes = $timeoutMinutes;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
