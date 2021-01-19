<?php

namespace LevelCredit\LevelCreditApi\Model\Request;

use LevelCredit\LevelCreditApi\Enum\TradelineSyncType;

class CreateTradelineSyncRequest extends BaseRequest
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

    /**
     * @param string $summaryEmail
     * @return static
     */
    public function setSummaryEmail(string $summaryEmail): self
    {
        $this->summaryEmail = $summaryEmail;

        return $this;
    }

    /**
     * @param int $timeoutMinutes
     * @return static
     */
    public function setTimeoutMinutes(int $timeoutMinutes): self
    {
        $this->timeoutMinutes = $timeoutMinutes;

        return $this;
    }

    /**
     * @param string $type
     * @return static
     * @see TradelineSyncType
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
