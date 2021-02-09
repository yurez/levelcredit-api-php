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
