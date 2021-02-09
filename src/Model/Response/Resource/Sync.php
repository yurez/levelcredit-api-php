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

namespace LevelCredit\LevelCreditApi\Model\Response\Resource;

use JMS\Serializer\Annotation as Serializer;
use LevelCredit\LevelCreditApi\Enum\TradelineSyncStatus;

class Sync extends BaseResource
{
    /**
     * @var string
     * @see TradelineSyncStatus
     *
     * @Serializer\Type("string")
     */
    protected $status;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     */
    protected $message;

    /**
     * @return string
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }
}
