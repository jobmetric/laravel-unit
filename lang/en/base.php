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
        "unit_type_not_in_unit_allow_types" => "The desired unit type is not in the allowed unit types",
    ],

    "messages" => [
        "found" => "Unit found.",
        "created" => "Unit created successfully.",
        "updated" => "Unit updated successfully.",
        "deleted" => "Unit deleted successfully.",
        "restored" => "Unit restored successfully.",
        "permanently_deleted" => "Unit permanently deleted successfully.",
        "change_default_value" => "Default value changed successfully.",
        "used_in" => "Unit used in :count places.",
        "attached" => "Unit attached successfully.",
        "detached" => "Unit detached successfully.",
    ],

    "exceptions" => [
        "model_unit_contract_not_found" => "Model ':model' not implements 'JobMetric\Unit\Contracts\UnitContract' interface!",
        "unit_not_found" => "Unit :number not found!",
        "type_not_found_in_allow_types" => "Type :type not found in allowed types!",
        "unit_type_default_value" => "The desired :unit unit must have a default value of 1 in the first step and you cannot enter another value",
        "unit_type_use_default_value" => "You have already entered the default value and you can no longer use the value 1",
        "unit_type_cannot_change_default_value" => "You cannot change the default value",
    ],

];
