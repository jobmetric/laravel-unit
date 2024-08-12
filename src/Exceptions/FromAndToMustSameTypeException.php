<?php

namespace JobMetric\Unit\Exceptions;

use Exception;
use Throwable;

class FromAndToMustSameTypeException extends Exception
{
    public function __construct(int $code = 400, ?Throwable $previous = null)
    {
        parent::__construct(trans('unit::base.exceptions.from_and_to_must_same_type'), $code, $previous);
    }
}
