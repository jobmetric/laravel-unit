<?php

namespace JobMetric\Unit\Exceptions;

use Exception;
use Throwable;

class UnitTypeCannotChangeDefaultValueException extends Exception
{
    public function __construct(int $code = 400, ?Throwable $previous = null)
    {
        parent::__construct(trans('unit::base.exceptions.unit_type_cannot_change_default_value'), $code, $previous);
    }
}
