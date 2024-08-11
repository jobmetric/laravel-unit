<?php

namespace JobMetric\Unit;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use JobMetric\Unit\Exceptions\ModelUnitContractNotFoundException;
use JobMetric\Unit\Http\Resources\UnitResource;
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
            return [
                'ok' => false,
                'message' => trans('unit::base.validation.errors'),
                'errors' => [
                    'form' => [
                        trans('unit::base.validation.object_not_found')
                    ]
                ],
                'status' => 404
            ];
        }

        $unitAllowTypes = $this->unitAllowTypes();

        if (!in_array($type, $unitAllowTypes)) {
            return [
                'ok' => false,
                'message' => trans('unit::base.validation.errors'),
                'errors' => [
                    'form' => [
                        trans('unit::base.validation.unit_type_not_in_unit_allow_types')
                    ]
                ],
                'status' => 422
            ];
        }

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

        return [
            'ok' => false,
            'message' => trans('unit::base.validation.errors'),
            'errors' => [
                'form' => [
                    trans('unit::base.validation.object_not_found')
                ]
            ],
            'status' => 404
        ];
    }

    /**
     * Get unit by type
     *
     * @param string $type
     *
     * @return MorphToMany
     */
    public function getMediaByType(string $type): MorphToMany
    {
        return $this->units()->wherePivot('type', $type);
    }
}
