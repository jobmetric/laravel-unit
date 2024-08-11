<?php

namespace JobMetric\Unit\Exceptions;

use Exception;
use Throwable;

class TypeNotFoundInAllowTypesException extends Exception
{
    public function __construct(string $type, int $code = 400, ?Throwable $previous = null)
    {
        parent::__construct(trans('unit::base.exceptions.type_not_found_in_allow_types', [
            'type' => $type,
        ]), $code, $previous);
    }
}
