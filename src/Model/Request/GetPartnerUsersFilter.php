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

use JMS\Serializer\Annotation as Serializer;
use LevelCredit\LevelCreditApi\Enum\UserStatus;

class GetPartnerUsersFilter extends BaseRequest
{
    use PaginationFilterTrait;
    use EmbedsFilterTrait;

    /**
     * @var string
     */
    protected $residentId;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     * @see UserStatus
     */
    protected $status;

    /**
     * @var \DateTime
     * @Serializer\Type("DateTime<'Y-m-d'>")
     */
    protected $createdAt;

    /**
     * @param string $residentId
     * @return static
     */
    public function setResidentId(string $residentId): self
    {
        $this->residentId = $residentId;

        return $this;
    }

    /**
     * @param string $email
     * @return static
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @param string $status
     * @return static
     * @see UserStatus
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @param \DateTime $createdAt
     * @return static
     */
    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
