<?php

namespace LevelCredit\LevelCreditApi\Model\Request;

use LevelCredit\LevelCreditApi\Enum\BankAccountType;

class BankAccount
{
    /**
     * @var string
     */
    protected $routing;

    /**
     * @var string
     */
    protected $account;

    /**
     * @var string
     * @see BankAccountType
     */
    protected $type;


}
