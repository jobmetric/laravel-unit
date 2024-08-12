<?php

namespace JobMetric\Unit\Exceptions;

use Exception;
use Throwable;

class UnitTypeNotInUnitAllowTypesException extends Exception
{
    public function __construct(string $type, int $code = 400, ?Throwable $previous = null)
    {
        parent::__construct(trans('unit::base.exceptions.unit_type_not_in_unit_allow_types', [
            'type' => $type,
        ]), $code, $previous);
    }
}
