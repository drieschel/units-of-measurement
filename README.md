# Drieschel's Units Of Measurement

This library is created to convert between different unit systems as well as converting metric values between their prefixes.

## Examples 

### Converting units
```php
<?php
use Drieschel\UnitsOfMeasurement\SiPrefixConverter;
use Drieschel\UnitsOfMeasurement\UnitCollection;
use Drieschel\UnitsOfMeasurement\UnitConverter;

// Own units and/or prefixes can be used by providing them in the constructor. Both is optional.
$units = UnitCollection::createAllUnits();
$siPrefixes = SiPrefixCollection::create();
$unitConverter = new UnitConverter($units, $siPrefixes);

// Converting miles to kilometre ($converted = 20.1168).
$converted = $unitConverter->convertBySymbol('mi', 'km', 12.5);
```

### Converting prefixes
Imagine you have an application which can handle values in microgram (µg) but the provided data is in kilogram (kg). The data can get converted from kilogram to microgram easily with this component.

```php
<?php
use Drieschel\UnitsOfMeasurement\SiPrefixCollection;
use Drieschel\UnitsOfMeasurement\SiPrefixConverter;
use Drieschel\UnitsOfMeasurement\UnitCollection;

// Own units and/or prefixes can be used by providing them in the constructor. Both is optional.
$units = UnitCollection::createAllUnits();
$siPrefixes = SiPrefixCollection::create();
$prefixConverter = new SiPrefixConverter($units, $siPrefixes);

// Converting litre to centilitre ($converted = 37.5).
// Note: The second argument is only the prefix symbol
$converted = $prefixConverter->convertByUnitSymbol('L', 'c', 3.75);

// Converting microgram to milligram ($converted = 0.0021).
$converted = $prefixConverter->convertByUnitSymbol('µg', 'm', 2.1);
```

### The Units
Collections of units can be created by using static factory methods from the unit collection. 
```php
<?php
use Drieschel\UnitsOfMeasurement\UnitCollection;
use Drieschel\UnitsOfMeasurement\PhysicalQuantitiesCollection;
// Create all defined units. If the US Customary system is preferred over the
// Imperial unit system, then the second (optional) argument has to be set to true.    
// It doesn't matter which system is preferred. Both unit versions (Imperial and USC)
// will be created every time, the preferred version just has no unit system prefix 
// in the symbol.
$physicalQuantities = PhysicalQuantitiesCollection::create();
$preferUsc = true;
$units = UnitCollection::createAllUnits($physicalQuantities, $preferUsc);

// A unit can get accessed by array notation or by get method.
// This is the US preferred version, because the $preferUsc flag was set to true.
$usGallon = $units['gal'];
$usGallon = $units->get('gal');
$impGallon = $units['imp gal'];
$impGallon = $units->get('imp gal');

// Create si base units only.
$siBaseUnits = UnitCollection::createSiBaseUnits($physicalQuantities);

// Create si derived units only.
$siDerivedUnits = UnitCollection::createSiDerivedUnits($physicalQuantities, $siBaseUnits);

// Create non si units only.
// The preferred non metric unit system can be chosen as in the "createAllUnits" method call.
$nonSiUnits = UnitCollection::createNonSiUnits($physicalQuantities, $siBaseUnits, $preferUsc);
```
### The physical quantities
The physical quantity collection can be accessed as multidimensional array (read only) to get a specific unit or to get a physical quantity. 

```php
<?php
use Drieschel\UnitsOfMeasurement\PhysicalQuantity;
use Drieschel\UnitsOfMeasurement\PhysicalQuantityCollection;
use Drieschel\UnitsOfMeasurement\SiBaseUnit;
use Drieschel\UnitsOfMeasurement\NonSiUnit;

// The $createUnits parameter can be set to true for creating all units at once.
// The $preferUsc can be set to true if the US Customary unit system is preferred over the Imperial unit system.
$createUnits = true;
$preferUsc = true;
$physicalQuantities = PhysicalQuantityCollection::create($createUnits, $preferUsc);

/** @var SiBaseUnit $metre */
$metre = $physicalQuantities['l']['m'];
$metre = $physicalQuantities->get('l')->get('m');

/** @var NonSiUnit $litre */
$litre = $physicalQuantities['V']['L'];

/** @var PhysicalQuantity $mass */
$mass = $physicalQuantities['m'];
```