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

use JMS\Serializer\Annotation as Serializer;

class AccessToken
{
    /**
     * @var string
     *
     * @Serializer\Type("string")
     */
    protected $accessToken;

    /**
     * @var int
     *
     * @Serializer\Type("integer")
     */
    protected $expiresIn;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     */
    protected $tokenType;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     */
    protected $scope;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     */
    protected $refreshToken;

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @return int
     */
    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }

    /**
     * @return string
     */
    public function getTokenType(): ?string
    {
        return $this->tokenType;
    }

    /**
     * @return string
     */
    public function getScope(): ?string
    {
        return $this->scope;
    }

    /**
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }
}
