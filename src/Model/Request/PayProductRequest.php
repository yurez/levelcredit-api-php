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
