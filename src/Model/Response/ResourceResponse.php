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

namespace LevelCredit\LevelCreditApi\Model\Response;

abstract class ResourceResponse extends BaseResponse
{
    /**
     * @var object
     */
    protected $resource;

    /**
     * @param object $resource
     */
    public function setResource(object $resource): void
    {
        $this->resource = $resource;
    }

    /**
     * @return object
     */
    public function getResource(): object
    {
        return $this->resource;
    }
}
