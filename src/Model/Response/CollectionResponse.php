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

use Doctrine\Common\Collections\ArrayCollection;

abstract class CollectionResponse extends BaseResponse
{
    /**
     * @var ArrayCollection
     */
    protected $elements;

    /**
     * @var int
     */
    protected $totalCount;

    public function __construct()
    {
        $this->elements = new ArrayCollection();
        $this->totalCount = 0;

        parent::__construct();
    }

    /**
     * @return ArrayCollection
     */
    public function getElements(): ArrayCollection
    {
        return $this->elements;
    }

    /**
     * @param ArrayCollection $collection
     */
    public function setElements(ArrayCollection $collection): void
    {
        $this->elements = $collection;
    }

    /**
     * @return int
     */
    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    /**
     * @param int $totalCount
     */
    public function setTotalCount(int $totalCount): void
    {
        $this->totalCount = $totalCount;
    }
}
