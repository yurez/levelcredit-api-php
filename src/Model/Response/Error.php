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

use JMS\Serializer\Annotation as Serializer;

class Error
{
    /**
     * @var string
     *
     * @Serializer\Type("string")
     */
    protected $parameter;

    /**
     * @var mixed
     *
     * @Serializer\Type("mixed")
     */
    protected $value;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     */
    protected $message;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     */
    protected $error;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     */
    protected $errorDescription;

    /**
     * @param string $message
     */
    public function __construct(string $message = '')
    {
        $this->message = $message;
        $this->errorDescription = $message;
    }

    /**
     * @return string
     */
    public function getParameter(): string
    {
        return $this->parameter ?: '_globals';
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message ?: $this->errorDescription;
    }
}
