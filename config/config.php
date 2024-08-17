<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Table Name
    |--------------------------------------------------------------------------
    |
    | Table name in database
    */

    "tables" => [
        'unit' => 'units',
        'unit_relation' => 'unit_relations'
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Image Size
    |--------------------------------------------------------------------------
    |
    | Default image size for media
    */

    "default_image_size" => [
        'width' => env('UNIT_DEFAULT_IMAGE_SIZE_WIDTH', 100),
        'height' => env('UNIT_DEFAULT_IMAGE_SIZE_HEIGHT', 100),
    ],

];
