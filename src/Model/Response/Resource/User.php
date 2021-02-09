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

namespace LevelCredit\LevelCreditApi\Model\Response\Resource;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;

class User extends BaseResource
{
    /**
     * @var string
     *
     * @Serializer\Type("string")
     */
    protected $firstName;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     */
    protected $lastName;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     */
    protected $email;

    /**
     * @var ArrayCollection|Subscription[]
     *
     * @Serializer\Type("ArrayCollection<LevelCredit\LevelCreditApi\Model\Response\Resource\Subscription>")
     */
    protected $subscriptions;

    /**
     * @return string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return ArrayCollection|Subscription[]
     */
    public function getSubscriptions(): ?ArrayCollection
    {
        return $this->subscriptions;
    }
}
