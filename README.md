# Drieschel's Units of Measurement

This library is created to convert between different unit systems as well as converting metric values between their prefixes.

## Examples 

### Converting units
```php
<?php
use Drieschel\UnitsOfMeasurement\UnitConverter;

$unitConverter = new UnitConverter();
```

### Converting prefixes
Imagine you have an application which can handle values in microgram (µg) but the provided data is in kilogram (kg). The data can get converted from kilogram to microgram easily with this component.

```php
<?php
use Drieschel\UnitsOfMeasurement\SiPrefixConverter;
// Use your own prefixes and/or units   
// by providing them in the constructor
$prefixConverter = new SiPrefixConverter();

// Convert kilogram into microgram ($converted = 99000000000)
$converted = $prefixConverter->convertByUnitSymbol('kg', 'µ', 99);

// Convert microgram into milligram ($converted = 0.000000042)
$converted = $prefixConverter->convertByUnitSymbol('µg', 'k', 42);
```