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

namespace LevelCredit\LevelCreditApi\Enum;

class OrderStatus
{
    public const CANCELLED = 'cancelled';
    public const COMPLETE = 'complete';
    public const ERROR = 'error';
    public const NEWONE = 'new';
    public const PENDING = 'pending';
    public const REFUNDED = 'refunded';
    public const REFUNDING = 'refunding';
    public const REISSUED = 'reissued';
    public const RETURNED = 'returned';
    public const SENDING = 'sending';
    public const OUT_FOR_DELIVERY = 'out_for_delivery';
}
