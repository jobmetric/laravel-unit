[Back To README.md](https://github.com/jobmetric/laravel-unit/blob/master/README.md)

# Introduction to Unit Service

The Unit class is part of a package developed to manage measurement units and convert them in Laravel projects. This class provides facilities such as creating, updating, deleting, and converting units of measure.

#### query

The query method creates a custom `QueryBuilder` for the Unit model. This method allows filtering, sorting, and selecting specific fields from the units. The query method serves as the core for other methods like `paginate` and `all`.

> $filter: An array of conditions to filter the results. These filters can be applied based on the fields available in the Unit model.
> 
> $with: An array of relationships that should be loaded with the units (eager loading).

Acceptable filters include the following fields:

- `id`
- `type`
- `value`
- `status`
- `created_at`
- `updated_at`

This method is typically used internally by the paginate and all methods but can also be used directly to create a custom query builder for specific needs.

#### paginate

The paginate method is used to retrieve a list of units with pagination. It uses the query method to apply filters and load relationships. The paginate method returns a `LengthAwarePaginator` object.

> $filter: Filter conditions to apply to the query.
> 
> $page_limit: The number of units to display per page. The default is 15.
> 
> $with: Relationships to be loaded with the units.

Example:

```php
$units = $unitService->paginate(['type' => 'weight'], 10, ['translations']);
```

> This code filters units of type `weight` and returns 10 units per page along with their translation's relationships.

#### all

The all method returns all units that match the given filter conditions without pagination. This method is suitable when you need to retrieve all records without dividing them into pages.

> $filter: Filter conditions to apply to the query.
> 
> $with: Relationships to be loaded with the units.

Example:

```php
$units = $unitService->all(['status' => true], ['translations']);
```

This code returns all units with a `status` of `true` along with their translation's relationships.

#### get

This method is used to get the information of a specific measurement unit based on its `id`. If the unit is not found, a `UnitNotFoundException` is thrown.

#### store

The store method is used to create and store a new unit in the database. This method handles validation, checks for specific business rules, and then saves the unit with the provided data.

> $data: An associative array containing the unit's data to be stored. This includes fields like `type`, `value`, `status`, and `translation` information such as `name`, `code`, `position`, and `description`.

Example:

```php
$data = [
    'type' => 'length',
    'value' => 2.54,
    'status' => true,
    'translation' => [
        'name' => 'Inch',
        'code' => 'in',
        'position' => 'right',
        'description' => 'Unit of length in the imperial system.'
    ]
];

$result = $unitService->store($data);

if ($result['ok']) {
    // Success handling
    $unit = $result['data'];
} else {
    // Error handling
    $errors = $result['errors'];
}
```

If any of the business rules are violated, the method throws specific exceptions like `UnitTypeDefaultValueException` or `UnitTypeUseDefaultValueException`. These exceptions should be caught and handled appropriately, either in the service layer or in the controller where the store method is called.

#### update

This method updates an existing unit of measure. Data is validated using `UpdateUnitRequest`. If an attempt is made to change the default unit value, a `UnitTypeCannotChangeDefaultValueException` exception is thrown.

#### delete

This method removes a unit of measurement. A `UnitTypeUsedInException` exception is thrown if the unit is in use. Also, if the unit is the default and there is more than one unit for its type, a `CannotDeleteDefaultValueException` is thrown.

#### changeDefaultValue

This method changes the default value of a unit and updates the values of all associated units.

#### usedIn

This method checks where an entity is used and returns a list of its `relationships`.

#### hasUsed

This method checks if a unit has been used somewhere. If the unit is not found, a `UnitNotFoundException` exception is thrown.

#### convert

This method is used to convert the value between two measurement units. If the type of the two entities are not the same, `FromAndToMustSameTypeException` is thrown.

- [Next To Has Unit](https://github.com/jobmetric/laravel-unit/blob/master/docs/has-unit.md)
