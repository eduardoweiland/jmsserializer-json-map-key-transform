<?php

declare(strict_types=1);

namespace Test;

use Hashids\Hashids;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use PHPUnit\Framework\TestCase;

class SerializerTest extends TestCase
{
    private const JSON = '{"items":{"Pe9xdL":"99.80","nelx6e":"154.00"}}';

    private SerializerInterface $serializer;

    protected function setUp(): void
    {
        $this->serializer = SerializerBuilder::create()
            ->addDefaultHandlers()
            ->configureHandlers(function(HandlerRegistry $registry) {
                $hashids = new Hashids('', 6);
                $handler = new HashidsSubscriber($hashids);
                $registry->registerSubscribingHandler($handler);
            })
            ->build();
    }

    public function testDeserialize(): void
    {
        $deserialized = $this->serializer->deserialize(self::JSON, Report::class, 'json');

        $this->assertInstanceOf(Report::class, $deserialized);
        $this->assertCount(2, $deserialized->items);

        $this->assertArrayHasKey(42, $deserialized->items);
        $this->assertEquals('99.80', $deserialized->items[42]);

        $this->assertArrayHasKey(191, $deserialized->items);
        $this->assertEquals('154.00', $deserialized->items[191]);
    }

    public function testSerialize(): void
    {
        $report = new Report();
        $report->addItem(42, '99.80');
        $report->addItem(191, '154.00');

        $serialized = $this->serializer->serialize($report, 'json');

        // This fails
        $this->assertJsonStringEqualsJsonString(self::JSON, $serialized);
    }
}
