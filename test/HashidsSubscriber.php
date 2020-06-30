<?php

declare(strict_types=1);

namespace Test;

use Hashids\HashidsInterface;
use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\JsonDeserializationVisitor;
use JMS\Serializer\JsonSerializationVisitor;

class HashidsSubscriber implements SubscribingHandlerInterface
{
    private HashidsInterface $hashids;

    public function __construct(HashidsInterface $hashids)
    {
        $this->hashids = $hashids;
    }

    public static function getSubscribingMethods()
    {
        return [
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'json',
                'type' => 'Hashid',
                'method' => 'encode',
            ],
            [
                'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                'format' => 'json',
                'type' => 'Hashid',
                'method' => 'decode',
            ],
        ];
    }

    public function encode(JsonSerializationVisitor $visitor, $decodedId, array $type, Context $context)
    {
        return $this->hashids->encode([(int)$decodedId]);
    }

    public function decode(JsonDeserializationVisitor $visitor, $encodedId, array $type, Context $context)
    {
        $result = $this->hashids->decode((string)$encodedId);

        if (isset($result[0])) {
            return $result[0];
        }

        return null;
    }
}
