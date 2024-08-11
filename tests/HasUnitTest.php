<?php

namespace JobMetric\Unit\Tests;

use App\Models\Product;
use Throwable;

class HasUnitTest extends BaseUnit
{
    /**
     * @throws Throwable
     */
    public function test_units_trait_relationship()
    {
        $product = new Product();
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\MorphToMany::class, $product->units());
    }

    /**
     * @throws Throwable
     */
    public function test_attach(): void
    {
        $product = $this->create_product();

        $unit = $this->create_unit();

        $attach = $product->attachUnit($unit->id, 'weight', 1);

        $this->assertIsArray($attach);

        $this->assertDatabaseHas(config('unit.tables.unit_relation'), [
            'unit_id' => $unit->id,
            'unitable_id' => $product->id,
            'unitable_type' => Product::class,
            'type' => 'weight',
            'value' => 1,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function test_detach(): void
    {
        $product = $this->create_product();

        $unit = $this->create_unit();

        $product->attachUnit($unit->id, 'weight', 1);

        $detach = $product->detachUnit($unit->id);

        $this->assertIsArray($detach);

        $this->assertDatabaseMissing(config('unit.tables.unit_relation'), [
            'unit_id' => $unit->id,
            'unitable_id' => $product->id,
            'unitable_type' => Product::class,
            'type' => 'weight',
            'value' => 1,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function test_get_unit_by_type(): void
    {
        $product = new Product();
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\MorphToMany::class, $product->getMediaByType('weight'));
    }
}
