<?php

namespace LevelCredit\LevelCreditApi\Model\Request;

use LevelCredit\LevelCreditApi\Enum\PaymentAccountType;

class VirtualTerminalPaymentAccount
{
    /**
     * @var PaymentAccountAddress
     */
    protected $address;

    /**
     * @var string
     * @see PaymentAccountType
     */
    protected $type;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var BankAccount
     */
    protected $bank;

    /**
     * @var CardAccount
     */
    protected $card;

    /**
     * @var CardAccount
     */
    protected $debitCard;
}
