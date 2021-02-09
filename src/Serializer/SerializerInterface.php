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

namespace LevelCredit\LevelCreditApi\Serializer;

use LevelCredit\LevelCreditApi\Exception\SerializerException;
use LevelCredit\LevelCreditApi\Model\Response\BaseResponse;
use Psr\Http\Message\ResponseInterface;

interface SerializerInterface
{
    /**
     * @param object $requestModel
     * @return string
     * @throws SerializerException
     */
    public function serializeRequest(object $requestModel): string;

    /**
     * @param object $query
     * @return array
     * @throws SerializerException
     */
    public function serializeQuery(object $query): array;

    /**
     * @param ResponseInterface $response
     * @param BaseResponse $responseModel
     * @param string|null $resourceClassName
     * @return BaseResponse
     * @throws SerializerException
     */
    public function deserializeResponse(
        ResponseInterface $response,
        BaseResponse $responseModel,
        string $resourceClassName = null
    ): BaseResponse;
}
