<?php

namespace JobMetric\Unit;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use JobMetric\Unit\Exceptions\ModelUnitContractNotFoundException;
use JobMetric\Unit\Exceptions\TypeNotFoundInAllowTypesException;
use JobMetric\Unit\Exceptions\UnitNotFoundException;
use JobMetric\Unit\Exceptions\UnitTypeNotInUnitAllowTypesException;
use JobMetric\Unit\Facades\Unit as UnitFacades;
use JobMetric\Unit\Http\Resources\UnitResource;
use JobMetric\Unit\Models\Unit;
use JobMetric\Unit\Models\UnitRelation;
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

    /**
     * attach unit
     *
     * @param int $unit_id
     * @param string $type
     * @param float $value
     *
     * @return array
     * @throws Throwable
     */
    public function attachUnit(int $unit_id, string $type, float $value): array
    {
        /**
         * @var Unit $unit
         */
        $unit = Unit::withTrashed()->find($unit_id);

        if (!$unit) {
            throw new UnitNotFoundException($unit_id);
        }

        $unitAllowTypes = $this->unitAllowTypes();

        if (!in_array($type, $unitAllowTypes)) {
            throw new UnitTypeNotInUnitAllowTypesException($type);
        }

        // @todo check duplicate type
        $this->units()->attach($unit_id, [
            'type' => $type,
            'value' => $value
        ]);

        return [
            'ok' => true,
            'message' => trans('unit::base.messages.attached'),
            'data' => UnitResource::make($unit),
            'status' => 200
        ];
    }

    /**
     * detach unit
     *
     * @param int $unit_id
     *
     * @return array
     * @throws Throwable
     */
    public function detachUnit(int $unit_id): array
    {
        foreach ($this->units as $unit) {
            if ($unit->id == $unit_id) {
                $data = UnitResource::make($unit);

                $this->units()->detach($unit_id);

                return [
                    'ok' => true,
                    'message' => trans('unit::base.messages.detached'),
                    'data' => $data,
                    'status' => 200
                ];
            }
        }

        throw new UnitNotFoundException($unit_id);
    }

    /**
     * Get unit by type
     *
     * @param string $type
     *
     * @return MorphToMany
     */
    public function getUnitByType(string $type): MorphToMany
    {
        return $this->units()->wherePivot('type', $type);
    }

    /**
     * Get unit value by type
     *
     * @param string $type
     * @param int|null $convert_unit_id
     *
     * @return array
     * @throws Throwable
     */
    public function getUnitValueByType(string $type, int $convert_unit_id = null): array
    {
        $unitAllowTypes = $this->unitAllowTypes();

        if (!in_array($type, $unitAllowTypes)) {
            throw new TypeNotFoundInAllowTypesException($type);
        }

        /**
         * @var Unit $convert_unit
         */
        $convert_unit = null;

        if ($convert_unit_id) {
            $convert_unit = Unit::query()->where('id', $convert_unit_id)->first();

            if (!$convert_unit) {
                throw new UnitNotFoundException($convert_unit_id);
            }

            $convert_unit->withTranslations(app()->getLocale());
        }

        /**
         * @var UnitRelation $unit_relation
         */
        $unit_relation = UnitRelation::query()
            ->where('unitable_id', $this->id)
            ->where('unitable_type', get_class($this))
            ->where('type', $type)
            ->first();

        if (!$unit_relation) {
            return [
                'translation' => null,
                'value' => null
            ];
        }

        if (!$convert_unit_id) {
            $convert_unit = Unit::query()->where('id', $unit_relation->unit_id)->first();

            if (!$convert_unit) {
                throw new UnitNotFoundException($unit_relation->unit_id);
            }

            $convert_unit->withTranslations(app()->getLocale());
        }

        $translation = translationResourceData($convert_unit->translations);

        $value = $unit_relation->value;

        return [
            'translation' => $translation[app()->getLocale()],
            'value' => UnitFacades::convert($unit_relation->unit_id, $convert_unit->id, $value)
        ];
    }
}
