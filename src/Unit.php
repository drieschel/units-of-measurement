<?php

namespace Drieschel\UnitsOfMeasurement;

class Unit extends AbstractComponent
{
    /**
     * @var UnitExpression
     */
    protected $unitExpression;

    /**
     * @var PhysicalQuantity
     */
    protected $physicalQuantity;

    /**
     * @var boolean
     */
    protected $siPrefixCompatible = false;

    /**
     * Unit constructor.
     * @param string $name
     * @param string $symbol
     * @param UnitExpression $unitExpression
     * @param PhysicalQuantity $physicalQuantity
     * @param boolean $siPrefixCompatible
     */
    public function __construct(string $name, string $symbol, UnitExpression $unitExpression, PhysicalQuantity $physicalQuantity, bool $siPrefixCompatible = false)
    {
        parent::__construct($name, $symbol);
        $this->unitExpression = $unitExpression;
        $this->physicalQuantity = $physicalQuantity;
        $this->siPrefixCompatible = $siPrefixCompatible;
        $physicalQuantity->set($this);
    }

    /**
     * @param float $nonSiValue
     * @return float
     */
    public function convertValueToSi(float $nonSiValue): float
    {
        return $nonSiValue * $this->siConversionFactor;
    }

    /**
     * @param float $siValue
     * @return float
     */
    public function convertValueFromSi(float $siValue): float
    {
        return $siValue / $this->siConversionFactor;
    }

    /**
     * @return PhysicalQuantity
     */
    public function getPhysicalQuantity(): PhysicalQuantity
    {
        return $this->physicalQuantity;
    }

    /**
     * @return bool
     */
    public function isSiPrefixCompatible(): bool
    {
        return $this->siPrefixCompatible;
    }

    /**
     * @return UnitExpression
     */
    public function getUnitExpression(): UnitExpression
    {
        return $this->unitExpression;
    }
}
