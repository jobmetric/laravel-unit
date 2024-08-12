<?php

namespace JobMetric\Unit\Exceptions;

use Exception;
use Throwable;

class UnitTypeUsedInException extends Exception
{
    public function __construct(int $unit_id, int $number, int $code = 400, ?Throwable $previous = null)
    {
        parent::__construct(trans('unit::base.exceptions.unit_type_used_in', [
            'unit_id' => $unit_id,
            'number' => $number,
        ]), $code, $previous);
    }
}
