<?php

namespace JobMetric\Unit\Events;

use JobMetric\Unit\Models\Unit;

class UnitUpdateEvent
{
    /**
     * Create a new event instance.
     */
    public function __construct(
        public readonly Unit  $unit,
        public readonly array $data
    )
    {
    }
}
