<?php

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
