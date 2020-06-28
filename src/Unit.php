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
     * @param string $defaultSymbol
     * @param UnitExpression $unitExpression
     * @param PhysicalQuantity $physicalQuantity
     * @param boolean $siPrefixCompatible
     */
    public function __construct(string $name, string $defaultSymbol, UnitExpression $unitExpression, PhysicalQuantity $physicalQuantity, bool $siPrefixCompatible = false)
    {
        parent::__construct($name, $defaultSymbol);
        $this->unitExpression = $unitExpression;
        $this->physicalQuantity = $physicalQuantity;
        $this->siPrefixCompatible = $siPrefixCompatible;
        $physicalQuantity->set($this);
    }

    /**
     * @param float $value
     * @return float
     */
    public function convertToSi(float $value): float
    {
        return $value * $this->unitExpression->getFactor();
    }

    /**
     * @param float $value
     * @return float
     */
    public function convertFromSi(float $value): float
    {
        return $value / $this->unitExpression->getFactor();
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
