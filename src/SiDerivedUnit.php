<?php

namespace Drieschel\UnitsOfMeasurement;

class SiDerivedUnit extends Unit
{
    /**
     * SiDerivedUnit constructor.
     * @param string $name
     * @param string $defaultSymbol
     * @param UnitExpression $unitExpression
     * @param PhysicalQuantity $physicalQuantity
     * @param bool $siPrefixCompatible
     */
    public function __construct(string $name, string $defaultSymbol, UnitExpression $unitExpression, PhysicalQuantity $physicalQuantity, bool $siPrefixCompatible = true)
    {
        parent::__construct($name, $defaultSymbol, $unitExpression, $physicalQuantity, $siPrefixCompatible);
    }

}
