<?php

namespace JobMetric\Unit\Tests;

use JobMetric\Unit\Enums\UnitTypeEnum;
use JobMetric\Unit\Exceptions\CannotDeleteDefaultValueException;
use JobMetric\Unit\Exceptions\UnitNotFoundException;
use JobMetric\Unit\Exceptions\UnitTypeCannotChangeDefaultValueException;
use JobMetric\Unit\Exceptions\UnitTypeDefaultValueException;
use JobMetric\Unit\Exceptions\UnitTypeUseDefaultValueException;
use JobMetric\Unit\Exceptions\UnitTypeUsedInException;
use JobMetric\Unit\Facades\Unit;
use JobMetric\Unit\Http\Resources\UnitRelationResource;
use JobMetric\Unit\Http\Resources\UnitResource;
use Throwable;

class UnitTest extends BaseUnit
{
    /**
     * @throws Throwable
     */
    public function test_store()
    {
        // store without default value
        try {
            $unit = Unit::store([
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

            $this->assertIsArray($unit);
        } catch (Throwable $e) {
            $this->assertInstanceOf(UnitTypeDefaultValueException::class, $e);
        }

        // store with default value
        $unit = Unit::store([
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

        $this->assertIsArray($unit);
        $this->assertTrue($unit['ok']);
        $this->assertEquals($unit['message'], trans('unit::base.messages.created'));
        $this->assertInstanceOf(UnitResource::class, $unit['data']);
        $this->assertEquals(201, $unit['status']);

        $this->assertDatabaseHas('units', [
            'type' => UnitTypeEnum::WEIGHT(),
            'value' => 1,
            'status' => true,
        ]);

        $this->assertDatabaseHas('translations', [
            'translatable_type' => 'JobMetric\Unit\Models\Unit',
            'translatable_id' => $unit['data']->id,
            'locale' => app()->getLocale(),
            'key' => 'name',
            'value' => 'Gram',
        ]);

        // store duplicate default value
        try {
            $unit = Unit::store([
                'type' => UnitTypeEnum::WEIGHT(),
                'value' => 1,
                'status' => true,
                'translation' => [
                    'name' => 'Ounce',
                    'code' => 'oz',
                    'position' => 'left',
                    'description' => 'The ounce is a unit of mass.',
                ],
            ]);

            $this->assertIsArray($unit);
        } catch (Throwable $e) {
            $this->assertInstanceOf(UnitTypeUseDefaultValueException::class, $e);
        }

        // store duplicate name
        $unit = Unit::store([
            'type' => UnitTypeEnum::WEIGHT(),
            'value' => 1000,
            'status' => true,
            'translation' => [
                'name' => 'Gram',
                'code' => 'g',
                'position' => 'left',
                'description' => 'The gram is a metric system unit of mass.',
            ],
        ]);

        $this->assertIsArray($unit);
        $this->assertFalse($unit['ok']);
        $this->assertEquals($unit['message'], trans('unit::base.validation.errors'));
        $this->assertEquals(422, $unit['status']);
    }

    /**
     * @throws Throwable
     */
    public function test_update()
    {
        // unit not found
        try {
            $unit = Unit::update(1000, [
                'value' => 1000,
                'status' => true,
                'translation' => [
                    'name' => 'Kilogram',
                    'code' => 'kg',
                    'position' => 'left',
                    'description' => 'The kilogram is the base unit of mass in the International System of Units (SI).',
                ],
            ]);

            $this->assertIsArray($unit);
        } catch (Throwable $e) {
            $this->assertInstanceOf(UnitNotFoundException::class, $e);
        }

        // store a unit
        $unitStore = Unit::store([
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

        // update with duplicate value
        try {
            $unit = Unit::update($unitStore['data']->id, [
                'value' => 1000,
                'status' => true,
                'translation' => [
                    'name' => 'Ounce',
                    'code' => 'oz',
                    'position' => 'left',
                    'description' => 'The ounce is a unit of mass.',
                ],
            ]);

            $this->assertIsArray($unit);
        } catch (Throwable $e) {
            $this->assertInstanceOf(UnitTypeCannotChangeDefaultValueException::class, $e);
        }

        // update with another name
        $unit = Unit::update($unitStore['data']->id, [
            'status' => true,
            'translation' => [
                'name' => 'Ounce',
                'code' => 'oz',
                'position' => 'left',
                'description' => 'The ounce is a unit of mass.',
            ],
        ]);

        $this->assertIsArray($unit);
        $this->assertTrue($unit['ok']);
        $this->assertEquals($unit['message'], trans('unit::base.messages.updated'));
        $this->assertInstanceOf(UnitResource::class, $unit['data']);
        $this->assertEquals(200, $unit['status']);

        $this->assertDatabaseHas('units', [
            'id' => $unit['data']->id,
            'type' => UnitTypeEnum::WEIGHT(),
            'value' => 1,
            'status' => true,
        ]);

        $this->assertDatabaseHas('translations', [
            'translatable_type' => 'JobMetric\Unit\Models\Unit',
            'translatable_id' => $unit['data']->id,
            'locale' => app()->getLocale(),
            'key' => 'name',
            'value' => 'Ounce',
        ]);
    }

    /**
     * @throws Throwable
     */
    public function test_get()
    {
        // store a unit
        $unitStore = Unit::store([
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

        // get the unit
        $unit = Unit::get($unitStore['data']->id);

        $this->assertIsArray($unit);
        $this->assertTrue($unit['ok']);
        $this->assertEquals($unit['message'], trans('unit::base.messages.found'));
        $this->assertInstanceOf(UnitResource::class, $unit['data']);
        $this->assertEquals(200, $unit['status']);

        $this->assertEquals($unit['data']->id, $unitStore['data']->id);
        $this->assertEquals($unit['data']->type, UnitTypeEnum::WEIGHT());
        $this->assertEquals(1, $unit['data']->value, 1);
        $this->assertTrue($unit['data']->status);

        // get the unit with a wrong id
        try {
            $unit = Unit::get(1000);

            $this->assertIsArray($unit);
        } catch (Throwable $e) {
            $this->assertInstanceOf(UnitNotFoundException::class, $e);
        }
    }

    /**
     * @throws Throwable
     */
    public function test_delete()
    {
        // store gram unit
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

        // store kilogram unit
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

        // delete the default unit
        try {
            $unit = Unit::delete($unitStoreGram['data']->id);

            $this->assertIsArray($unit);
        } catch (Throwable $e) {
            $this->assertInstanceOf(CannotDeleteDefaultValueException::class, $e);
        }

        // delete the kilogram unit
        $unit = Unit::delete($unitStoreKilogram['data']->id);

        $this->assertIsArray($unit);
        $this->assertTrue($unit['ok']);
        $this->assertEquals($unit['message'], trans('unit::base.messages.deleted'));
        $this->assertEquals(200, $unit['status']);

        $this->assertDatabaseMissing('units', [
            'id' => $unitStoreKilogram['data']->id,
        ]);

        // delete the kilogram unit again
        try {
            $unit = Unit::delete($unitStoreKilogram['data']->id);

            $this->assertIsArray($unit);
        } catch (Throwable $e) {
            $this->assertInstanceOf(UnitNotFoundException::class, $e);
        }

        // attach the unit to the product
        $product = $this->create_product();

        $product->attachUnit($unitStoreGram['data']->id, UnitTypeEnum::WEIGHT(), 300);

        // delete the unit
        try {
            $unit = Unit::delete($unitStoreGram['data']->id);

            $this->assertIsArray($unit);
        } catch (Throwable $e) {
            $this->assertInstanceOf(UnitTypeUsedInException::class, $e);
        }
    }

    /**
     * @throws Throwable
     */
    public function test_change_default_value()
    {
        // Store gram unit
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

        // Store kilogram unit
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

        // Store ton unit
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

        // Change default value with wrong unit id
        try {
            $changeDefaultValue = Unit::changeDefaultValue(1000);

            $this->assertIsArray($changeDefaultValue);
        } catch (Throwable $e) {
            $this->assertInstanceOf(UnitNotFoundException::class, $e);
        }

        // Change default value with kilogram unit id
        $changeDefaultValue = Unit::changeDefaultValue($unitStoreKilogram['data']->id);

        $this->assertIsArray($changeDefaultValue);
        $this->assertTrue($changeDefaultValue['ok']);
        $this->assertEquals($changeDefaultValue['message'], trans('unit::base.messages.change_default_value'));
        $this->assertEquals(200, $changeDefaultValue['status']);

        // Check value in database
        $this->assertDatabaseHas('units', [
            'id' => $unitStoreKilogram['data']->id,
            'value' => 1,
        ]);

        // Check value in database
        $this->assertDatabaseHas('units', [
            'id' => $unitStoreGram['data']->id,
            'value' => 0.001,
        ]);

        // Check value in database
        $this->assertDatabaseHas('units', [
            'id' => $unitStoreTon['data']->id,
            'value' => 1000,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function test_all()
    {
        // Store a unit
        Unit::store([
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

        // Get the units
        $getUnits = Unit::all();

        $this->assertCount(1, $getUnits);

        $getUnits->each(function ($unit) {
            $this->assertInstanceOf(UnitResource::class, $unit);
        });
    }

    /**
     * @throws Throwable
     */
    public function test_pagination()
    {
        // Store a unit
        Unit::store([
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

        // Paginate the units
        $paginateUnits = Unit::paginate();

        $this->assertCount(1, $paginateUnits);

        $paginateUnits->each(function ($unit) {
            $this->assertInstanceOf(UnitResource::class, $unit);
        });

        $this->assertIsInt($paginateUnits->total());
        $this->assertIsInt($paginateUnits->perPage());
        $this->assertIsInt($paginateUnits->currentPage());
        $this->assertIsInt($paginateUnits->lastPage());
        $this->assertIsArray($paginateUnits->items());
    }

    /**
     * @throws Throwable
     */
    public function test_used_in()
    {
        $product = $this->create_product();

        // Store a unit
        $unitStore = Unit::store([
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

        // Attach the unit to the product
        $attachUnit = $product->attachUnit($unitStore['data']->id, UnitTypeEnum::WEIGHT(), 300);

        $this->assertIsArray($attachUnit);
        $this->assertTrue($attachUnit['ok']);
        $this->assertEquals($attachUnit['message'], trans('unit::base.messages.attached'));
        $this->assertInstanceOf(UnitResource::class, $attachUnit['data']);
        $this->assertEquals(200, $attachUnit['status']);

        // Get the unit used in the product
        $usedIn = Unit::usedIn($unitStore['data']->id);

        $this->assertIsArray($usedIn);
        $this->assertTrue($usedIn['ok']);
        $this->assertEquals($usedIn['message'], trans('unit::base.messages.used_in', [
            'count' => 1
        ]));
        $usedIn['data']->each(function ($dataUsedIn) {
            $this->assertInstanceOf(UnitRelationResource::class, $dataUsedIn);
        });
        $this->assertEquals(200, $usedIn['status']);

        // Get the unit used in the product with a wrong unit id
        try {
            $usedIn = Unit::usedIn(1000);

            $this->assertIsArray($usedIn);
        } catch (Throwable $e) {
            $this->assertInstanceOf(UnitNotFoundException::class, $e);
        }
    }

    /**
     * @throws Throwable
     */
    public function test_has_used()
    {
        $product = $this->create_product();

        // Store a unit
        $unitStore = Unit::store([
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

        // Attach the unit to the product
        $attachUnit = $product->attachUnit($unitStore['data']->id, UnitTypeEnum::WEIGHT(), 300);

        $this->assertIsArray($attachUnit);
        $this->assertTrue($attachUnit['ok']);
        $this->assertEquals($attachUnit['message'], trans('unit::base.messages.attached'));
        $this->assertInstanceOf(UnitResource::class, $attachUnit['data']);
        $this->assertEquals(200, $attachUnit['status']);

        // check has used in
        $usedIn = Unit::hasUsed($unitStore['data']->id);

        $this->assertTrue($usedIn);

        // check with wrong unit id
        try {
            $usedIn = Unit::hasUsed(1000);
        } catch (Throwable $e) {
            $this->assertInstanceOf(UnitNotFoundException::class, $e);
        }
    }

    /**
     * @throws Throwable
     */
    public function test_convert()
    {
        // Store a unit
        $unitStore = Unit::store([
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
        $unitStore2 = Unit::store([
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
        $unitStore3 = Unit::store([
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

        // Convert the unit
        $convert = Unit::convert($unitStore2['data']->id, $unitStore3['data']->id, 2);

        $this->assertIsFloat($convert);
        $this->assertEquals(0.002, $convert);

        // Convert the unit
        $convert = Unit::convert($unitStore['data']->id, $unitStore2['data']->id, 50);

        $this->assertIsFloat($convert);
        $this->assertEquals(0.05, $convert);
    }
}
