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
        "errors" => "Validation errors occurred."
    ],

    "messages" => [
        "found" => "Unit found.",
        "created" => "Unit created successfully.",
        "updated" => "Unit updated successfully.",
        "deleted" => "Unit deleted successfully.",
        "change_default_value" => "Default value changed successfully.",
        "used_in" => "Unit used in ':count' places.",
        "attached" => "Unit attached successfully.",
        "detached" => "Unit detached successfully.",
    ],

    "exceptions" => [
        "model_unit_contract_not_found" => "Model ':model' not implements 'JobMetric\Unit\Contracts\UnitContract' interface!",
        "unit_not_found" => "Unit ':number' not found!",
        "type_not_found_in_allow_types" => "Type ':type' not found in allowed types!",
        "unit_type_default_value" => "The desired ':unit' unit must have a default value of 1 in the first step and you cannot enter another value",
        "unit_type_use_default_value" => "You have already entered the default value and you can no longer use the value 1",
        "unit_type_cannot_change_default_value" => "You cannot change the default value",
        "unit_type_not_in_unit_allow_types" => "Unit type ':type' not in unit allow types!",
        "unit_type_used_in" => "Unit number ':unit_id' used in ':number' places!",
        "cannot_delete_default_value" => "You cannot remove the default value until other items have been removed!",
        "from_and_to_must_same_type" => "The from and to of the conversion must be of the same type!",
    ],

    "fields" => [
        "weight" => "Weight",
        "length" => "Length",
        "currency" => "Currency",
        "number" => "Number",
        "crypto" => "Crypto",
        "volume" => "Volume",
        "temperature" => "Temperature",
        "area" => "Area",
        "pressure" => "Pressure",
        "speed" => "Speed",
        "force" => "Force",
        "time" => "Time",
        "torque" => "Torque",
        "energy" => "Energy",
        "frequency" => "Frequency",
        "power" => "Power",
        "acceleration" => "Acceleration",
        "data_transfer" => "Data Transfer",
        "data_storage" => "Data Storage",
        "angle" => "Angle",
        "density" => "Density",
        "mass_flow" => "Mass Flow",
        "volumetric_flow" => "Volumetric Flow",
        "electric_current" => "Electric Current",
        "heat_transfer_coefficient" => "Heat Transfer Coefficient",
    ],

    "field_name" => ":field Unit",
    "field_enter" => "Enter :field",
    "field_select" => "Select a :field",

];
