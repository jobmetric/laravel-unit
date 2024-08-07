<?php

namespace JobMetric\Unit;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use JobMetric\Unit\Exceptions\ModelUnitContractNotFoundException;
use JobMetric\Unit\Models\Unit;
use Throwable;

/**
 * Trait HasUnit
 *
 * @package JobMetric\Unit
 *
 * @property Unit[] units
 *
 * @method morphToMany(string $class, string $string, string $string1)
 */
trait HasUnit
{
    /**
     * boot has unit
     *
     * @return void
     * @throws Throwable
     */
    public static function bootHasUnit(): void
    {
        if (!in_array('JobMetric\Unit\Contracts\UnitContract', class_implements(self::class))) {
            throw new ModelUnitContractNotFoundException(self::class);
        }
    }

    /**
     * unit has many relationships
     *
     * @return MorphToMany
     */
    public function units(): MorphToMany
    {
        return $this->morphToMany(Unit::class, 'unitable', config('unit.tables.unit_relation'))
            ->withPivot('type')
            ->withTimestamps(['created_at']);
    }
}
