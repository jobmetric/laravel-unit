<?php

use JobMetric\Unit\Facades\Unit;

if (!function_exists('unitConvert')) {
    /**
     * unit convert
     *
     * @param int $from_unit_id
     * @param int $to_unit_id
     * @param float $value
     *
     * @return float
     * @throws Throwable
     */
    function unitConvert(int $from_unit_id, int $to_unit_id, float $value): float
    {
        return Unit::convert($from_unit_id, $to_unit_id, $value);
    }
}
