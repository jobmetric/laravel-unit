<?php

namespace JobMetric\Unit\Tests;

use App\Models\Product;
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
}
