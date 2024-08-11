<?php

namespace JobMetric\Unit\Tests;

use JobMetric\Unit\Enums\UnitTypeEnum;
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
        $this->assertFalse($unit['ok']);
        $this->assertEquals($unit['message'], trans('unit::base.validation.errors'));
        $this->assertEquals($unit['errors'], [
            'form' => [
                trans('unit::base.validation.unit_type_default_value_error', [
                    'unit' => UnitTypeEnum::WEIGHT()
                ])
            ]
        ]);
        $this->assertEquals(422, $unit['status']);

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
        $this->assertFalse($unit['ok']);
        $this->assertEquals($unit['message'], trans('unit::base.validation.errors'));
        $this->assertEquals($unit['errors'], [
            'form' => [
                trans('unit::base.validation.unit_type_use_default_value_error', [
                    'unit' => UnitTypeEnum::WEIGHT()
                ])
            ]
        ]);
        $this->assertEquals(422, $unit['status']);

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
        $this->assertFalse($unit['ok']);
        $this->assertEquals($unit['message'], trans('unit::base.validation.errors'));
        $this->assertEquals($unit['errors'], [
            'form' => [
                trans('unit::base.validation.object_not_found')
            ]
        ]);
        $this->assertEquals(404, $unit['status']);

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
        $this->assertFalse($unit['ok']);
        $this->assertEquals($unit['message'], trans('unit::base.validation.errors'));
        $this->assertEquals($unit['errors'], [
            'value' => [
                trans('unit::base.validation.unit_type_cannot_change_default_value')
            ]
        ]);
        $this->assertEquals(422, $unit['status']);

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
        $unit = Unit::get(1000);

        $this->assertIsArray($unit);
        $this->assertFalse($unit['ok']);
        $this->assertEquals($unit['message'], trans('unit::base.validation.errors'));
        $this->assertEquals($unit['errors'], [
            'form' => [
                trans('unit::base.validation.object_not_found')
            ]
        ]);
        $this->assertEquals(404, $unit['status']);
    }

    /**
     * @throws Throwable
     */
    public function test_delete()
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

        // delete the unit
        $unit = Unit::delete($unitStore['data']->id);

        $this->assertIsArray($unit);
        $this->assertTrue($unit['ok']);
        $this->assertEquals($unit['message'], trans('unit::base.messages.deleted'));
        $this->assertEquals(200, $unit['status']);

        $this->assertSoftDeleted('units', [
            'id' => $unitStore['data']->id,
        ]);

        // delete the unit again
        $unit = Unit::delete($unitStore['data']->id);

        $this->assertIsArray($unit);
        $this->assertFalse($unit['ok']);
        $this->assertEquals($unit['message'], trans('unit::base.validation.errors'));
        $this->assertEquals($unit['errors'], [
            'form' => [
                trans('unit::base.validation.object_not_found')
            ]
        ]);
        $this->assertEquals(404, $unit['status']);
    }

    /**
     * @throws Throwable
     */
    public function test_restore()
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

        // delete the unit
        Unit::delete($unitStore['data']->id);

        // restore the unit
        $unit = Unit::restore($unitStore['data']->id);

        $this->assertIsArray($unit);
        $this->assertTrue($unit['ok']);
        $this->assertEquals($unit['message'], trans('unit::base.messages.restored'));
        $this->assertEquals(200, $unit['status']);

        $this->assertNotSoftDeleted('units', [
            'id' => $unitStore['data']->id,
        ]);

        // restore the unit again
        $unit = Unit::restore($unitStore['data']->id);

        $this->assertIsArray($unit);
        $this->assertFalse($unit['ok']);
        $this->assertEquals($unit['message'], trans('unit::base.validation.errors'));
        $this->assertEquals($unit['errors'], [
            'form' => [
                trans('unit::base.validation.object_not_found')
            ]
        ]);
        $this->assertEquals(404, $unit['status']);
    }

    /**
     * @throws Throwable
     */
    public function test_force_delete()
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

        // delete the unit
        Unit::delete($unitStore['data']->id);

        // force deletes the unit
        $unit = Unit::forceDelete($unitStore['data']->id);

        $this->assertIsArray($unit);
        $this->assertTrue($unit['ok']);
        $this->assertEquals($unit['message'], trans('unit::base.messages.permanently_deleted'));
        $this->assertEquals(200, $unit['status']);

        $this->assertDatabaseMissing('units', [
            'id' => $unitStore['data']->id,
        ]);

        // force deletes the unit again
        $unit = Unit::forceDelete($unitStore['data']->id);

        $this->assertIsArray($unit);
        $this->assertFalse($unit['ok']);
        $this->assertEquals($unit['message'], trans('unit::base.validation.errors'));
        $this->assertEquals($unit['errors'], [
            'form' => [
                trans('unit::base.validation.object_not_found')
            ]
        ]);
        $this->assertEquals(404, $unit['status']);
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
    }
}
