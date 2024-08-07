<?php

namespace JobMetric\Unit\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Spatie\QueryBuilder\QueryBuilder query(array $filter = [], array $with = [], string $mode = null)
 * @method static \Illuminate\Http\Resources\Json\AnonymousResourceCollection paginate(array $filter = [], int $page_limit = 15, array $with = [], string $mode = null)
 * @method static \Illuminate\Http\Resources\Json\AnonymousResourceCollection all(array $filter = [], array $with = [], string $mode = null)
 *
 * @see \JobMetric\Unit\Unit
 */
class Unit extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return \JobMetric\Unit\Unit::class;
    }
}
