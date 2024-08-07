<?php

namespace JobMetric\Unit\Tests;

use App\Models\Product;
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
}
