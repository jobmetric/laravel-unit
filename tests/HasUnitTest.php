<?php

namespace JobMetric\Unit\Tests;

use App\Models\Product;
use JobMetric\Unit\Enums\UnitTypeEnum;
use JobMetric\Unit\Facades\Unit;
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

    /**
     * @throws Throwable
     */
    public function test_get_unit_value_by_type(): void
    {
        // Store a unit
        $unitStoreGram = Unit::store([
            'type' => UnitTypeEnum::WEIGHT(),
            'value' => 1,
            'status' => true,
            'translation' => [
                'name' => 'Gram',
                'code' => 'g',
                'position' => 'left',
                'description' => 'The gram is a metric system unit of mass.',
            ],
        ]);

        // Store another unit
        $unitStoreKilogram = Unit::store([
            'type' => UnitTypeEnum::WEIGHT(),
            'value' => 1000,
            'status' => true,
            'translation' => [
                'name' => 'Kilogram',
                'code' => 'kg',
                'position' => 'left',
                'description' => 'The kilogram is the base unit of mass in the International System of Units (SI).',
            ],
        ]);

        // Store another unit
        $unitStoreTon = Unit::store([
            'type' => UnitTypeEnum::WEIGHT(),
            'value' => 1000000,
            'status' => true,
            'translation' => [
                'name' => 'Ton',
                'code' => 't',
                'position' => 'left',
                'description' => 'The ton is a unit of weight.',
            ],
        ]);

        // Create a product
        $product = $this->create_product();

        // Attach the first unit to the product
        $product->attachUnit($unitStoreGram['data']->id, 'weight', 10);

        // get unit value by type - gram
        $unitValue = $product->getUnitValueByType('weight', $unitStoreGram['data']->id);

        $this->assertIsArray($unitValue);
        $this->assertIsArray($unitValue['translation']);
        $this->assertEquals(10, $unitValue['value']);

        // get unit value by type - kilogram
        $unitValue = $product->getUnitValueByType('weight', $unitStoreKilogram['data']->id);

        $this->assertIsArray($unitValue);
        $this->assertIsArray($unitValue['translation']);
        $this->assertEquals(0.01, $unitValue['value']);

        // get unit value by type - ton
        $unitValue = $product->getUnitValueByType('weight', $unitStoreTon['data']->id);

        $this->assertIsArray($unitValue);
        $this->assertIsArray($unitValue['translation']);
        $this->assertEquals(0.00001, $unitValue['value']);

        // get unit value by type - not convert
        $unitValue = $product->getUnitValueByType('weight');

        $this->assertIsArray($unitValue);
        $this->assertIsArray($unitValue['translation']);
        $this->assertEquals(10, $unitValue['value']);
    }
}
