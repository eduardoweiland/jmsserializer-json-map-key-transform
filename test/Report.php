<?php

declare(strict_types=1);

namespace Test;

use JMS\Serializer\Annotation as JMS;

class Report
{
    /**
     * @var array<int,string>
     * @JMS\Type("array<Hashid,string>")
     */
    public array $items = [];

    public function addItem(int $id, string $value): void
    {
        $this->items[$id] = $value;
    }
}
