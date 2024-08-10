<?php

namespace JobMetric\Unit\Tests;

use JobMetric\Unit\Enums\UnitTypeEnum;
use JobMetric\Unit\Facades\Unit;
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
    public function test_show()
    {
    }

    /**
     * @throws Throwable
     */
    public function test_used_in()
    {
    }

    /**
     * @throws Throwable
     */
    public function test_has_used()
    {
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
    public function test_pagination()
    {
    }

    /**
     * @throws Throwable
     */
    public function test_all()
    {
    }
}
