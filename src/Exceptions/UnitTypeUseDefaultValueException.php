<?php

namespace JobMetric\Unit\Exceptions;

use Exception;
use Throwable;

class UnitTypeUseDefaultValueException extends Exception
{
    public function __construct(string $type, int $code = 400, ?Throwable $previous = null)
    {
        parent::__construct(trans('unit::base.exceptions.unit_type_use_default_value', [
            'type' => $type,
        ]), $code, $previous);
    }
}
