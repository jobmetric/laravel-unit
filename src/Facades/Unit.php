<?php

namespace JobMetric\Unit\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Spatie\QueryBuilder\QueryBuilder query(array $filter = [], array $with = [], string $mode = null)
 * @method static \Illuminate\Http\Resources\Json\AnonymousResourceCollection paginate(array $filter = [], int $page_limit = 15, array $with = [], string $mode = null)
 * @method static \Illuminate\Http\Resources\Json\AnonymousResourceCollection all(array $filter = [], array $with = [], string $mode = null)
 * @method static array get(int $unit_id, array $with = [], string $mode = null, string $locale = null)
 * @method static array store(array $data)
 * @method static array update(int $unit_id, array $data)
 * @method static array delete(int $unit_id)
 * @method static array restore(int $unit_id)
 * @method static array forceDelete(int $unit_id)
 * @method static array changeDefaultValue(int $unit_id)
 * @method static array usedIn(int $unit_id)
 * @method static bool hasUsed(int $unit_id)
 * @method static float convert(int $from_unit_id, int $to_unit_id, float $value)
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
