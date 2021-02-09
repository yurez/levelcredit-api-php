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
