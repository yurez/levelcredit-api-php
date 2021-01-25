<?php

namespace LevelCredit\LevelCreditApi\Model\Request;

use LevelCredit\LevelCreditApi\Enum\BankAccountType;

class BankAccount extends BaseRequest
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

    /**
     * @param string $routing
     * @return static
     */
    public function setRouting(string $routing): self
    {
        $this->routing = $routing;

        return $this;
    }

    /**
     * @param string $account
     * @return static
     */
    public function setAccount(string $account): self
    {
        $this->account = $account;

        return $this;
    }

    /**
     * @param string $type
     * @see BankAccountType
     * @return static
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
