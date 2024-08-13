<?php

namespace JobMetric\Unit\Enums;

use JobMetric\PackageCore\Enums\EnumToArray;

/**
 * @method static WEIGHT()
 * @method static LENGTH()
 * @method static CURRENCY()
 * @method static NUMBER()
 * @method static CRYPTO()
 * @method static VOLUME()
 * @method static TEMPERATURE()
 * @method static AREA()
 * @method static PRESSURE()
 * @method static SPEED()
 * @method static FORCE()
 * @method static TIME()
 * @method static TORQUE()
 * @method static ENERGY()
 * @method static FREQUENCY()
 * @method static POWER()
 * @method static ACCELERATION()
 * @method static DATA_TRANSFER()
 * @method static DATA_STORAGE()
 * @method static ANGLE()
 * @method static DENSITY()
 * @method static MASS_FLOW()
 * @method static VOLUMETRIC_FLOW()
 * @method static ELECTRIC_CURRENT()
 * @method static HEAT_TRANSFER_COEFFICIENT()
 */
enum UnitTypeEnum: string
{
    use EnumToArray;

    case WEIGHT = "weight";                                                 /* واحد های وزن */
    case LENGTH = "length";                                                 /* واحد های طول */
    case CURRENCY = "currency";                                             /* واحد های ارز */
    case NUMBER = "number";                                                 /* واحد های تعداد */
    case CRYPTO = "crypto";                                                 /* واحد های رمز ارز */
    case VOLUME = "volume";                                                 /* واحد های حجم */
    case TEMPERATURE = "temperature";                                       /* واحد های دما */
    case AREA = "area";                                                     /* واحد های مساحت */
    case PRESSURE = "pressure";                                             /* واحد های فشار */
    case SPEED = "speed";                                                   /* واحد های سرعت */
    case FORCE = "force";                                                   /* واحد های نیرو */
    case TIME = "time";                                                     /* واحد های زمان */
    case TORQUE = "torque";                                                 /* واحد های گشتاور */
    case ENERGY = "energy";                                                 /* واحد های انرژی */
    case FREQUENCY = "frequency";                                           /* واحد های فرکانس */
    case POWER = "power";                                                   /* واحد های توان */
    case ACCELERATION = "acceleration";                                     /* واحد های شتاب */
    case DATA_TRANSFER = "data_transfer";                                   /* واحد های انتقال داده */
    case DATA_STORAGE = "data_storage";                                     /* واحد های ذخیره سازی داده */
    case ANGLE = "angle";                                                   /* واحد های زاویه */
    case DENSITY = "density";                                               /* واحد های چگالی */
    case MASS_FLOW = "mass_flow";                                           /* واحد های جریان جرم */
    case VOLUMETRIC_FLOW = "volumetric_flow";                               /* واحد های جریان حجم */
    case ELECTRIC_CURRENT = "electric_current";                             /* واحد های جریان الکتریکی */
    case HEAT_TRANSFER_COEFFICIENT = "heat_transfer_coefficient";           /* واحد های ضریب انتقال حرارت */
}
