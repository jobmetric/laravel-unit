<?php

namespace JobMetric\Unit\Enums;

use JobMetric\PackageCore\Enums\EnumToArray;

/**
 * @method static WEIGHT()
 * @method static LENGTH()
 * @method static CURRENCY()
 * @method static NUMBER()
 * @method static CRYPTO()
 */
enum UnitTypeEnum: string
{
    use EnumToArray;

    case WEIGHT = "weight";
    case LENGTH = "length";
    case CURRENCY = "currency";
    case NUMBER = "number";
    case CRYPTO = "crypto";
}
