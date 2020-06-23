<?php

namespace Drieschel\UnitsOfMeasurement;

class SiBaseUnit extends Unit
{
    /**
     * SiBaseUnit constructor.
     * @param string $name
     * @param string $symbol
     * @param PhysicalQuantity $physicalQuantity
     */
    public function __construct(string $name, string $symbol, PhysicalQuantity $physicalQuantity)
    {
        parent::__construct($name, $symbol, new UnitExpression(1., new SiBaseUnitTerm($this)), $physicalQuantity, true);
    }
}
