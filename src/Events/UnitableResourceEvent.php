<?php

namespace JobMetric\Unit\Events;

class UnitableResourceEvent
{
    /**
     * The unitable model instance.
     *
     * @var mixed
     */
    public mixed $unitable;

    /**
     * The resource to be filled by the listener.
     *
     * @var mixed|null
     */
    public mixed $resource;

    /**
     * Create a new event instance.
     *
     * @param mixed $unitable
     */
    public function __construct(mixed $unitable)
    {
        $this->unitable = $unitable;
        $this->resource = null;
    }
}
