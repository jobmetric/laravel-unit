<?php

namespace JobMetric\Unit\Seeders;

use Illuminate\Database\Seeder;
use JobMetric\Unit\Enums\UnitTypeEnum;
use JobMetric\Unit\Facades\Unit;

class VolumeUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Liter
        Unit::store([
            'type' => UnitTypeEnum::VOLUME(),
            'value' => 1,
            'status' => true,
            'translation' => [
                'name' => 'Liter',
                'code' => 'L',
                'position' => 'left',
                'description' => 'The liter is a metric unit of volume.',
            ],
        ]);

        // kiloliter
        Unit::store([
            'type' => UnitTypeEnum::VOLUME(),
            'value' => 1000,
            'status' => true,
            'translation' => [
                'name' => 'Kiloliter',
                'code' => 'kL',
                'position' => 'left',
                'description' => 'The kiloliter is a metric unit of volume.',
            ],
        ]);

        // mililiter
        Unit::store([
            'type' => UnitTypeEnum::VOLUME(),
            'value' => 0.001,
            'status' => true,
            'translation' => [
                'name' => 'Mililiter',
                'code' => 'mL',
                'position' => 'left',
                'description' => 'The mililiter is a metric unit of volume.',
            ],
        ]);

        // deciliter
        Unit::store([
            'type' => UnitTypeEnum::VOLUME(),
            'value' => 0.1,
            'status' => true,
            'translation' => [
                'name' => 'Deciliter',
                'code' => 'dL',
                'position' => 'left',
                'description' => 'The deciliter is a metric unit of volume.',
            ],
        ]);

        // centiliter
        Unit::store([
            'type' => UnitTypeEnum::VOLUME(),
            'value' => 0.01,
            'status' => true,
            'translation' => [
                'name' => 'Centiliter',
                'code' => 'cL',
                'position' => 'left',
                'description' => 'The centiliter is a metric unit of volume.',
            ],
        ]);

        // microliter
        Unit::store([
            'type' => UnitTypeEnum::VOLUME(),
            'value' => 0.000001,
            'status' => true,
            'translation' => [
                'name' => 'Microliter',
                'code' => 'µL',
                'position' => 'left',
                'description' => 'The microliter is a metric unit of volume.',
            ],
        ]);

        // nanoliter
        Unit::store([
            'type' => UnitTypeEnum::VOLUME(),
            'value' => 0.000000001,
            'status' => true,
            'translation' => [
                'name' => 'Nanoliter',
                'code' => 'nL',
                'position' => 'left',
                'description' => 'The nanoliter is a metric unit of volume.',
            ],
        ]);

        // dekaliter
        Unit::store([
            'type' => UnitTypeEnum::VOLUME(),
            'value' => 10,
            'status' => true,
            'translation' => [
                'name' => 'Dekaliter',
                'code' => 'daL',
                'position' => 'left',
                'description' => 'The dekaliter is a metric unit of volume.',
            ],
        ]);

        // cc
        Unit::store([
            'type' => UnitTypeEnum::VOLUME(),
            'value' => 0.001,
            'status' => true,
            'translation' => [
                'name' => 'Cubic Centimeter',
                'code' => 'cc',
                'position' => 'left',
                'description' => 'The cubic centimeter is a metric unit of volume.',
            ],
        ]);

        // cubic meter
        Unit::store([
            'type' => UnitTypeEnum::VOLUME(),
            'value' => 1000,
            'status' => true,
            'translation' => [
                'name' => 'Cubic Meter',
                'code' => 'm³',
                'position' => 'left',
                'description' => 'The cubic meter is a metric unit of volume.',
            ],
        ]);
    }
}
