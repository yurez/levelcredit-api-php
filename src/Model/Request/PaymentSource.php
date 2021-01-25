<?php

namespace LevelCredit\LevelCreditApi\Model\Request;

use LevelCredit\LevelCreditApi\Enum\PaymentAccountType;

class PaymentSource extends BaseRequest
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

    /**
     * @param PaymentAccountAddress $address
     * @return static
     */
    public function setAddress(PaymentAccountAddress $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @param string $type
     * @see PaymentAccountType
     * @return static
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param string $name
     * @return static
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param BankAccount $bank
     * @return static
     */
    public function setBank(BankAccount $bank): self
    {
        $this->bank = $bank;

        return $this;
    }

    /**
     * @param CardAccount $card
     * @return static
     */
    public function setCard(CardAccount $card): self
    {
        $this->card = $card;

        return $this;
    }

    /**
     * @param CardAccount $debitCard
     * @return static
     */
    public function setDebitCard(CardAccount $debitCard): self
    {
        $this->debitCard = $debitCard;

        return $this;
    }
}
