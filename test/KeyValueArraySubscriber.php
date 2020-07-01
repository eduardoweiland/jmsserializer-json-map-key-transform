<?php

declare(strict_types=1);

namespace Test;

use Exception;
use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use function count;

class KeyValueArraySubscriber implements SubscribingHandlerInterface
{
    public static function getSubscribingMethods()
    {
        return [
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'json',
                'type' => 'KeyValueArray',
                'method' => 'handle',
            ],
            [
                'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                'format' => 'json',
                'type' => 'KeyValueArray',
                'method' => 'handle',
            ],
        ];
    }

    /**
     * @param mixed $visitor
     * @param mixed $map
     * @param array $type
     * @param Context $context
     * @return array<mixed,mixed>
     */
    public function handle($visitor, $map, array $type, Context $context): array
    {
        if (!is_iterable($map)) {
            return [];
        }

        if (count($type['params']) !== 2) {
            throw new Exception('KeyValueArray requires key and value types');
        }

        $keyType = $type['params'][0];
        $valueType = $type['params'][1];
        $navigator = $context->getNavigator();
        $output = [];

        foreach ($map as $key => $value) {
            $outputKey = $navigator->accept($key, $keyType);
            $outputValue = $navigator->accept($value, $valueType);
            $output[$outputKey] = $outputValue;
        }

        return $output;
    }
}

