<?php

declare(strict_types=1);

namespace Test;

use JMS\Serializer\Annotation as JMS;

class ReportWithWorkaround
{
    /**
     * @var array<int,string>
     * @JMS\Type("KeyValueArray<Hashid,string>")
     */
    public array $items = [];

    public function addItem(int $id, string $value): void
    {
        $this->items[$id] = $value;
    }
}
