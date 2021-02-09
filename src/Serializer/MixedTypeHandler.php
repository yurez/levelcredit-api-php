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

use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Context;

class MixedTypeHandler implements SubscribingHandlerInterface
{

    /**
     * @return array
     */
    public static function getSubscribingMethods(): array
    {
        return [
            [
                'direction' => GraphNavigatorInterface::DIRECTION_DESERIALIZATION,
                'format' => 'json',
                'type' => 'mixed',
                'method' => 'deserializeMixedValue',
            ],
        ];
    }

    /**
     * Handler need for correct deserialize of mixed type
     *
     * @param DeserializationVisitorInterface $visitor
     * @param mixed $value
     * @param array $type
     * @param Context $context
     * @return mixed
     */
    public function deserializeMixedValue(
        DeserializationVisitorInterface $visitor,
        $value,
        array $type,
        Context $context
    ) {
        return $value;
    }
}
