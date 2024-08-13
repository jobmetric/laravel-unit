[Back To README.md](https://github.com/jobmetric/laravel-unit/blob/master/README.md)

# Introduction to Helper Function

In this section, we will explain the helper functions that are used in the unit package.

### unitConvert

This function is used to convert value the unit to another unit.

The return value of this function is a float value.

```php
unitConvert(int $from_unit_id, int $to_unit_id, float $value);
```

> The `from_unit_id` value is the ID of the unit that you want to convert from.
> 
> The `to_unit_id` value is the ID of the unit that you want to convert to.
> 
> The `value` value is the value that you want to convert.
