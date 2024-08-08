<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Base Unit Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during Unit for
    | various messages that we need to display to the user.
    |
    */

    "validation" => [
        "errors" => "Validation errors occurred.",
        "object_not_found" => "Unit not found!",
        "unit_type_default_value_error" => "The desired :unit unit must have a default value of 1 in the first step and you cannot enter another value",
        "unit_type_use_default_value_error" => "You have already entered the default value and you can no longer use the value 1",
        "unit_type_cannot_change_default_value" => "You cannot change the default value",
    ],

    "messages" => [
        "found" => "Unit found.",
        "created" => "Unit created successfully.",
        "updated" => "Unit updated successfully.",
        "deleted" => "Unit deleted successfully.",
        "restored" => "Unit restored successfully.",
        "permanently_deleted" => "Unit permanently deleted successfully.",
        "change_default_value" => "Default value changed successfully.",
    ],

    "exceptions" => [
        "model_unit_contract_not_found" => "Model ':model' not implements 'JobMetric\Unit\Contracts\UnitContract' interface!",
    ],

];
