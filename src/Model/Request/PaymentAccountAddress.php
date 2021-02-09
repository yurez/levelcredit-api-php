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

class PaymentAccountAddress extends BaseRequest
{
    /**
     * @var string
     */
    protected $street;

    /**
     * @var string
     */
    protected $city;

    /**
     * @var string
     */
    protected $state;

    /**
     * @var string
     */
    protected $zip;

    /**
     * @param string $street
     * @return static
     */
    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    /**
     * @param string $city
     * @return static
     */
    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @param string $state
     * @return static
     */
    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @param string $zip
     * @return static
     */
    public function setZip(string $zip): self
    {
        $this->zip = $zip;

        return $this;
    }
}
