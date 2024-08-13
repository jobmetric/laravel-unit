<?php

namespace JobMetric\Unit\Seeders;

use Illuminate\Database\Seeder;
use JobMetric\Unit\Enums\UnitTypeEnum;
use JobMetric\Unit\Facades\Unit;

class LengthUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Meter
        Unit::store([
            'type' => UnitTypeEnum::LENGTH(),
            'value' => 1,
            'status' => true,
            'translation' => [
                'name' => 'Meter',
                'code' => 'm',
                'position' => 'left',
                'description' => 'The meter is the base unit of length in the International System of Units.',
            ],
        ]);

        // Centimeter
        Unit::store([
            'type' => UnitTypeEnum::LENGTH(),
            'value' => 0.01,
            'status' => true,
            'translation' => [
                'name' => 'Centimeter',
                'code' => 'cm',
                'position' => 'left',
                'description' => 'The centimeter is a unit of length in the metric system.',
            ],
        ]);

        // Kilometer
        Unit::store([
            'type' => UnitTypeEnum::LENGTH(),
            'value' => 1000,
            'status' => true,
            'translation' => [
                'name' => 'Kilometer',
                'code' => 'km',
                'position' => 'left',
                'description' => 'The kilometer is a unit of length in the metric system.',
            ],
        ]);

        // Inch
        Unit::store([
            'type' => UnitTypeEnum::LENGTH(),
            'value' => 0.0254,
            'status' => true,
            'translation' => [
                'name' => 'Inch',
                'code' => 'in',
                'position' => 'left',
                'description' => 'The inch is a unit of length in the imperial and United States customary systems of measurement.',
            ],
        ]);

        // Foot - Feet
        Unit::store([
            'type' => UnitTypeEnum::LENGTH(),
            'value' => 0.3048,
            'status' => true,
            'translation' => [
                'name' => 'Foot',
                'code' => 'ft',
                'position' => 'left',
                'description' => 'The foot is a unit of length in the imperial and US customary systems of measurement.',
            ],
        ]);

        // Yard
        Unit::store([
            'type' => UnitTypeEnum::LENGTH(),
            'value' => 0.9144,
            'status' => true,
            'translation' => [
                'name' => 'Yard',
                'code' => 'yd',
                'position' => 'left',
                'description' => 'The yard is a unit of length in the imperial and US customary systems of measurement.',
            ],
        ]);

        // Mile
        Unit::store([
            'type' => UnitTypeEnum::LENGTH(),
            'value' => 1609.34,
            'status' => true,
            'translation' => [
                'name' => 'Mile',
                'code' => 'mi',
                'position' => 'left',
                'description' => 'The mile is a unit of length in the imperial and US customary systems of measurement.',
            ],
        ]);

        // Nautical Mile
        Unit::store([
            'type' => UnitTypeEnum::LENGTH(),
            'value' => 1852,
            'status' => true,
            'translation' => [
                'name' => 'Nautical Mile',
                'code' => 'nmi',
                'position' => 'left',
                'description' => 'The nautical mile is a unit of length used in air and marine navigation.',
            ],
        ]);

        // Point
        Unit::store([
            'type' => UnitTypeEnum::LENGTH(),
            'value' => 0.000352778,
            'status' => true,
            'translation' => [
                'name' => 'Point',
                'code' => 'pt',
                'position' => 'left',
                'description' => 'The point is a unit of length used in typography.',
            ],
        ]);

        // Decimeter
        Unit::store([
            'type' => UnitTypeEnum::LENGTH(),
            'value' => 0.1,
            'status' => true,
            'translation' => [
                'name' => 'Decimeter',
                'code' => 'dm',
                'position' => 'left',
                'description' => 'The decimeter is a unit of length in the metric system.',
            ],
        ]);

        // Micron
        Unit::store([
            'type' => UnitTypeEnum::LENGTH(),
            'value' => 0.000001,
            'status' => true,
            'translation' => [
                'name' => 'Micron',
                'code' => 'µm',
                'position' => 'left',
                'description' => 'The micron is a unit of length in the metric system.',
            ],
        ]);

        // Militimeter
        Unit::store([
            'type' => UnitTypeEnum::LENGTH(),
            'value' => 0.001,
            'status' => true,
            'translation' => [
                'name' => 'Militimeter',
                'code' => 'mm',
                'position' => 'left',
                'description' => 'The millimeter is a unit of length in the metric system.',
            ],
        ]);

        // Micrometer
        Unit::store([
            'type' => UnitTypeEnum::LENGTH(),
            'value' => 0.000001,
            'status' => true,
            'translation' => [
                'name' => 'Micrometer',
                'code' => 'µm',
                'position' => 'left',
                'description' => 'The micrometer is a unit of length in the metric system.',
            ],
        ]);

        // Nanometer
        Unit::store([
            'type' => UnitTypeEnum::LENGTH(),
            'value' => 0.000000001,
            'status' => true,
            'translation' => [
                'name' => 'Nanometer',
                'code' => 'nm',
                'position' => 'left',
                'description' => 'The nanometer is a unit of length in the metric system.',
            ],
        ]);

        // Chain
        Unit::store([
            'type' => UnitTypeEnum::LENGTH(),
            'value' => 20.1168,
            'status' => true,
            'translation' => [
                'name' => 'Chain',
                'code' => 'ch',
                'position' => 'left',
                'description' => 'The chain is a unit of length in the imperial and US customary systems of measurement.',
            ],
        ]);
    }
}
