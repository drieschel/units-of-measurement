# Drieschel's Units Of Measurement

This library is created to convert between different unit systems as well as converting metric values between their prefixes.

## Examples 

### Converting units
```php
<?php
use Drieschel\UnitsOfMeasurement\UnitConverter;

// Use your own units and/or prefixes    
// by providing them in the constructor
$unitConverter = new UnitConverter();

// Converting miles to kilometre ($converted = 20.1168)
$converted = $unitConverter->convertBySymbol('mi', 'km', 12.5);
```

### Converting prefixes
Imagine you have an application which can handle values in microgram (µg) but the provided data is in kilogram (kg). The data can get converted from kilogram to microgram easily with this component.

```php
<?php
use Drieschel\UnitsOfMeasurement\SiPrefixConverter;

// Use your own units and/or prefixes    
// by providing them in the constructor
$prefixConverter = new SiPrefixConverter();

// Converting litre to centilitre ($converted = 37.5)
$converted = $prefixConverter->convertByUnitSymbol('L', 'c', 3.75);

// Converting microgram to milligram ($converted = 0.0021)
$converted = $prefixConverter->convertByUnitSymbol('µg', 'm', 2.1);
```

### The physical quantity collection
The physical quantity collection can be accessed as multidimensional array (read only) to get a specific unit or to get a physical quantity

```php
<?php
use Drieschel\UnitsOfMeasurement\PhysicalQuantity;
use Drieschel\UnitsOfMeasurement\PhysicalQuantityCollection;
use Drieschel\UnitsOfMeasurement\SiBaseUnit;
use Drieschel\UnitsOfMeasurement\NonSiUnit;

// Set $createUnits parameter to true for creating units at once
$physicalQuantities = PhysicalQuantityCollection::create(true);

/** @var SiBaseUnit $metre */
$metre = $physicalQuantities['l']['m'];

/** @var NonSiUnit $litre */
$litre = $physicalQuantities['V']['L'];

/** @var PhysicalQuantity $mass */
$mass = $physicalQuantities['m'];
