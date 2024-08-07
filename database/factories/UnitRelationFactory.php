<?php

namespace JobMetric\Unit\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use JobMetric\Unit\Models\UnitRelation;

/**
 * @extends Factory<UnitRelation>
 */
class UnitRelationFactory extends Factory
{
    protected $model = UnitRelation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'unit_id' => null,
            'unitable_type' => null,
            'unitable_id' => null,
            'type' => null
        ];
    }

    /**
     * set unit_id
     *
     * @param int $unit_id
     *
     * @return static
     */
    public function setUnitId(int $unit_id): static
    {
        return $this->state(fn(array $attributes) => [
            'unit_id' => $unit_id
        ]);
    }

    /**
     * set unitable
     *
     * @param string $unitable_type
     * @param int $unitable_id
     *
     * @return static
     */
    public function setUnitable(string $unitable_type, int $unitable_id): static
    {
        return $this->state(fn(array $attributes) => [
            'unitable_type' => $unitable_type,
            'unitable_id' => $unitable_id
        ]);
    }

    /**
     * set type
     *
     * @param string $type
     *
     * @return static
     */
    public function setType(string $type): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => $type
        ]);
    }
}
