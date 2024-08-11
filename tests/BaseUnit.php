<?php

namespace JobMetric\Unit\Tests;

use App\Models\Product;
use JobMetric\Unit\Enums\UnitTypeEnum;
use JobMetric\Unit\Facades\Unit;
use JobMetric\Unit\Models\Unit as UnitModels;
use Tests\BaseDatabaseTestCase as BaseTestCase;

class BaseUnit extends BaseTestCase
{
    /**
     * create a fake product
     *
     * @return Product
     */
    public function create_product(): Product
    {
        return Product::factory()->create();
    }

    /**
     * create a fake unit
     *
     * @return UnitModels
     */
    public function create_unit(): UnitModels
    {
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

        return UnitModels::find(1);
    }
}
