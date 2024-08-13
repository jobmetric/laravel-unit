<?php

namespace JobMetric\Unit\Seeders;

use Illuminate\Database\Seeder;
use JobMetric\Unit\Enums\UnitTypeEnum;
use JobMetric\Unit\Facades\Unit;

class WeightUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // gram
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

        // kilogram
        Unit::store([
            'type' => UnitTypeEnum::WEIGHT(),
            'value' => 1000,
            'status' => true,
            'translation' => [
                'name' => 'Kilogram',
                'code' => 'kg',
                'position' => 'left',
                'description' => 'The kilogram is the base unit of mass in the International System of Units.',
            ],
        ]);

        // ton
        Unit::store([
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

        // pound
        Unit::store([
            'type' => UnitTypeEnum::WEIGHT(),
            'value' => 453.592,
            'status' => true,
            'translation' => [
                'name' => 'Pound',
                'code' => 'lb',
                'position' => 'left',
                'description' => 'The pound or pound-mass is a unit of mass used in the imperial, United States customary and other systems of measurement.',
            ],
        ]);

        // carat
        Unit::store([
            'type' => UnitTypeEnum::WEIGHT(),
            'value' => 0.2,
            'status' => true,
            'translation' => [
                'name' => 'Carat',
                'code' => 'ct',
                'position' => 'left',
                'description' => 'The carat is a unit of mass used for measuring gemstones and pearls.',
            ],
        ]);

        // ounce
        Unit::store([
            'type' => UnitTypeEnum::WEIGHT(),
            'value' => 28.3495,
            'status' => true,
            'translation' => [
                'name' => 'Ounce',
                'code' => 'oz',
                'position' => 'left',
                'description' => 'The ounce is a unit of mass used in most British derived customary systems of measurement.',
            ],
        ]);

        // milligram
        Unit::store([
            'type' => UnitTypeEnum::WEIGHT(),
            'value' => 0.001,
            'status' => true,
            'translation' => [
                'name' => 'Milligram',
                'code' => 'mg',
                'position' => 'left',
                'description' => 'The milligram is a unit of mass in the metric system.',
            ],
        ]);

        // centigram
        Unit::store([
            'type' => UnitTypeEnum::WEIGHT(),
            'value' => 0.01,
            'status' => true,
            'translation' => [
                'name' => 'Centigram',
                'code' => 'cg',
                'position' => 'left',
                'description' => 'The centigram is a unit of mass in the metric system.',
            ],
        ]);

        // decigram
        Unit::store([
            'type' => UnitTypeEnum::WEIGHT(),
            'value' => 0.1,
            'status' => true,
            'translation' => [
                'name' => 'Decigram',
                'code' => 'dg',
                'position' => 'left',
                'description' => 'The decigram is a unit of mass in the metric system.',
            ],
        ]);

        // dekagram
        Unit::store([
            'type' => UnitTypeEnum::WEIGHT(),
            'value' => 10,
            'status' => true,
            'translation' => [
                'name' => 'Dekagram',
                'code' => 'dag',
                'position' => 'left',
                'description' => 'The dekagram is a unit of mass in the metric system.',
            ],
        ]);

        // megagram
        Unit::store([
            'type' => UnitTypeEnum::WEIGHT(),
            'value' => 1000000,
            'status' => true,
            'translation' => [
                'name' => 'Megagram',
                'code' => 'Mg',
                'position' => 'left',
                'description' => 'The megagram is a unit of mass in the metric system.',
            ],
        ]);

        // megatonne
        Unit::store([
            'type' => UnitTypeEnum::WEIGHT(),
            'value' => 1000000000,
            'status' => true,
            'translation' => [
                'name' => 'Megatonne',
                'code' => 'Mt',
                'position' => 'left',
                'description' => 'The megatonne is a unit of mass in the metric system.',
            ],
        ]);

        // microgram
        Unit::store([
            'type' => UnitTypeEnum::WEIGHT(),
            'value' => 0.000001,
            'status' => true,
            'translation' => [
                'name' => 'Microgram',
                'code' => 'µg',
                'position' => 'left',
                'description' => 'The microgram is a unit of mass in the metric system.',
            ],
        ]);
    }
}
