[Back To README.md](https://github.com/jobmetric/laravel-unit/blob/master/README.md)

# Introduction to Unit Component

This section of the documentation contains descriptions of the Field component and its associated view, implemented in the JobMetric\Unit package. This component is used to create an input field along with a units selector in forms.

### Field class (in the path JobMetric\Unit\View\Components\Field)

The Field class is a `Blade component` in `Laravel` that allows the user to easily create a numeric input field with a dropdown of units. This component automatically loads and displays the entities associated with the specified data type.

#### Class features:

> type (string): The type of unit (such as weight, length, etc.) that the input field should display.
> 
> dataInput (string): Default value for numeric input field.
> 
> dataSelect (string): The default value for the unit selector.

### How to use the Field component

To use the Field component, you need to add the following code to the blade file where you want to use the Field component:

```php
<x-unit-field type="weight" data-input="100" data-select="20"/>
```

> The `type` attribute is required and specifies the type of unit that the input field should display.
> 
> The `data-input` attribute is optional and specifies the default value for the numeric input field.
> 
> The `data-select` attribute is optional and specifies the default value for the unit selector.

- [Next To Helper Function](https://github.com/jobmetric/laravel-unit/blob/master/docs/helper.md)
