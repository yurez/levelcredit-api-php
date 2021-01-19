<?php

namespace LevelCredit\LevelCreditApi\Model\Request;

class PayProductRequest
{
    /**
     * @var string
     */
    protected $objectUrl;

    /**
     * @var string
     */
    protected $paymentAccountUrl;

    /**
     * @var VirtualTerminalPaymentAccount
     */
    protected $paymentAccount;

    /**
     * @var float
     */
    protected $amount;
}
