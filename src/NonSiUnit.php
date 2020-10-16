<?php

namespace Drieschel\UnitsOfMeasurement;

class NonSiUnit extends Unit
{
    /**
     * @var boolean
     */
    protected $acceptedForUseWithSi = false;

    /**
     * NonSiUnit constructor.
     * @param string $name
     * @param string $defaultSymbol
     * @param UnitExpression $unitExpression
     * @param PhysicalQuantity $physicalQuantity
     * @param bool $siPrefixCompatible
     * @param bool $acceptedForUseWithSi
     * @throws CollectionException
     */
    public function __construct(string $name, string $defaultSymbol, UnitExpression $unitExpression, PhysicalQuantity $physicalQuantity, bool $siPrefixCompatible = false, bool $acceptedForUseWithSi = false)
    {
        $this->acceptedForUseWithSi = $acceptedForUseWithSi;
        parent::__construct($name, $defaultSymbol, $unitExpression, $physicalQuantity, $siPrefixCompatible);
    }

    /**
     * @return bool
     */
    public function isAcceptedForUseWithSi(): bool
    {
        return $this->acceptedForUseWithSi;
    }
}
