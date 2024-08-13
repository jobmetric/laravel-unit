[Back To README.md](https://github.com/jobmetric/laravel-unit/blob/master/README.md)

# Introduction to HasUnit Trait

This file contains a Trait called HasUnit, which is added to different models in Laravel to add the capabilities related to units. These capabilities include attaching a unit to a model, detaching a unit from a model, and capturing units attached to a model. Also, this Trait handles rules and exceptions for entities.

## How to use HasUnit

To use the HasUnit Trait, you need to add it to the model you want to use. For example, if you want to add the HasUnit Trait to the Product model, you need to add the following code to the Product model:

```php
namespace App\Models;

use JobMetric\Unit\HasUnit;
use JobMetric\Unit\Contracts\UnitContract;

class Product extends Model implements UnitContract
{
    use HasUnit;
    
    /**
     * unit allows the type.
     *
     * @return array
     */
    public function unitAllowTypes(): array
    {
        return [
            UnitTypeEnum::WEIGHT(),
            UnitTypeEnum::LENGTH(),
            UnitTypeEnum::CURRENCY()
        ];
    }
}
```

#### attachUnit(int $unit_id, string $type, float $value)

This method is used to attach a unit to a model. The method accepts three parameters: unit_id, type, and value. The unit_id is the ID of the unit you want to attach to the model. The type is the type of the unit you want to attach to the model. The value is the value of the unit you want to attach to the model. The method returns the attached unit.

#### detachUnit(int $unit_id)

This method is used to detach a unit from a model. The method accepts one parameter: unit_id. The unit_id is the ID of the unit you want to detach from the model. The method returns the detached unit.

#### getUnitByType(string $type)

This method is used to get the unit by type. The method accepts one parameter: type. The type is the type of the unit you want to get. The method returns the unit by type.

#### getUnitValueByType(string $type, int $convert_unit_id = null)

This method is used to get the unit value by type. The method accepts two parameters: type and convert_unit_id. The type is the type of the unit you want to get. The convert_unit_id is the ID of the unit you want to convert to. The method returns the unit value by type.

- [Next To Component](https://github.com/jobmetric/laravel-unit/blob/master/docs/component.md)
