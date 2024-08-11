<?php

namespace JobMetric\Unit\Exceptions;

use Exception;
use Throwable;

class UnitNotFoundException extends Exception
{
    public function __construct(int $number, int $code = 400, ?Throwable $previous = null)
    {
        parent::__construct(trans('unit::base.exceptions.unit_not_found', [
            'number' => $number,
        ]), $code, $previous);
    }
}
