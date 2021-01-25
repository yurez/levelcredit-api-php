<?php

namespace LevelCredit\LevelCreditApi\Model\Request;

class PayProductRequest extends BaseRequest
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
     * @var PaymentSource
     */
    protected $paymentAccount;

    /**
     * @var float
     */
    protected $amount;

    /**
     * @param string $objectUrl
     * @return static
     */
    public function setObjectUrl(string $objectUrl): self
    {
        $this->objectUrl = $objectUrl;

        return $this;
    }

    /**
     * @param string $paymentAccountUrl
     * @return static
     */
    public function setPaymentAccountUrl(string $paymentAccountUrl): self
    {
        $this->paymentAccountUrl = $paymentAccountUrl;

        return $this;
    }

    /**
     * @param PaymentSource $paymentAccount
     * @return static
     */
    public function setPaymentAccount(PaymentSource $paymentAccount): self
    {
        $this->paymentAccount = $paymentAccount;

        return $this;
    }

    /**
     * @param float $amount
     * @return static
     */
    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }
}
