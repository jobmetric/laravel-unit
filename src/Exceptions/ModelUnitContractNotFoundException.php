<?php

namespace JobMetric\Unit\Exceptions;

use Exception;
use Throwable;

class ModelUnitContractNotFoundException extends Exception
{
    public function __construct(string $model, int $code = 400, ?Throwable $previous = null)
    {
        parent::__construct(trans('unit::base.exceptions.model_unit_contract_not_found', [
            'model' => $model
        ]), $code, $previous);
    }
}
