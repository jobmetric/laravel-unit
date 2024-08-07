<?php

namespace JobMetric\Unit\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use JobMetric\Unit\Enums\UnitTypeEnum;
use JobMetric\Unit\Models\Unit;

/**
 * @extends Factory<Unit>
 */
class UnitFactory extends Factory
{
    protected $model = Unit::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->shuffleArray(UnitTypeEnum::values()),
            'value' => 1,
            'status' => $this->faker->boolean
        ];
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

    /**
     * set value
     *
     * @param float $value
     *
     * @return static
     */
    public function setValue(float $value): static
    {
        return $this->state(fn(array $attributes) => [
            'value' => $value
        ]);
    }

    /**
     * set status
     *
     * @param bool $status
     *
     * @return static
     */
    public function setStatus(bool $status): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => $status
        ]);
    }
}
