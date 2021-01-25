<?php

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
