<?php

namespace LevelCredit\LevelCreditApi\Model\Request;

class CardAccount extends BaseRequest
{
    /**
     * @var string
     */
    protected $account;

    /**
     * @var string
     */
    protected $cvv;

    /**
     * @var string
     */
    protected $expiration;

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
     * @param string $cvv
     * @return static
     */
    public function setCvv(string $cvv): self
    {
        $this->cvv = $cvv;

        return $this;
    }

    /**
     * @param string $expiration
     * @return static
     */
    public function setExpiration(string $expiration): self
    {
        $this->expiration = $expiration;

        return $this;
    }
}
