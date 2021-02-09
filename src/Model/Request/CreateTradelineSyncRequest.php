<?php

/* Copyright(c) 2021 by RentTrack, Inc.  All rights reserved.
 *
 * This software contains proprietary and confidential information of
 * RentTrack Inc., and its suppliers.  Except as may be set forth
 * in the license agreement under which this software is supplied, use,
 * disclosure, or  reproduction is prohibited without the prior express
 * written consent of RentTrack, Inc.
 *
 * The license terms of service are hosted at https://github.com/levelcredit/levelcredit-api-php/blob/master/LICENSE
 */

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
