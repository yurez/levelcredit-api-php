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

trait EmbedsFilterTrait
{
    /**
     * @var array
     */
    protected $embeds;

    /**
     * @param string $embedded
     * @return static
     */
    public function addEmbedded(string $embedded): self
    {
        $this->embeds[] = $embedded;

        return $this;
    }
}
