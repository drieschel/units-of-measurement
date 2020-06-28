<?php

namespace Drieschel\UnitsOfMeasurement;

class SiBaseUnit extends Unit
{
    /**
     * SiBaseUnit constructor.
     * @param string $name
     * @param string $defaultSymbol
     * @param PhysicalQuantity $physicalQuantity
     */
    public function __construct(string $name, string $defaultSymbol, PhysicalQuantity $physicalQuantity)
    {
        parent::__construct($name, $defaultSymbol, new UnitExpression(1., new SiBaseUnitTerm($this)), $physicalQuantity, true);
    }
}
