<?php

namespace JobMetric\Unit\Seeders;

use Illuminate\Database\Seeder;
use JobMetric\Unit\Enums\UnitTypeEnum;
use JobMetric\Unit\Facades\Unit;

class TemperatureUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Celsius
        Unit::store([
            'type' => UnitTypeEnum::TEMPERATURE(),
            'value' => 1,
            'status' => true,
            'translation' => [
                'name' => 'Celsius',
                'code' => '°C',
                'position' => 'left',
                'description' => 'The Celsius scale, also known as the centigrade scale, is a temperature scale used by the International System of Units (SI).',
            ],
        ]);
    }
}
