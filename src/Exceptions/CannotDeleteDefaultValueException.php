<?php

namespace JobMetric\Unit\Exceptions;

use Exception;
use Throwable;

class CannotDeleteDefaultValueException extends Exception
{
    public function __construct(int $code = 400, ?Throwable $previous = null)
    {
        parent::__construct(trans('unit::base.exceptions.cannot_delete_default_value'), $code, $previous);
    }
}
