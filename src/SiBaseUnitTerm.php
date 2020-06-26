<?php

namespace Drieschel\UnitsOfMeasurement;

class SiBaseUnitTerm
{
    /**
     * @var SiBaseUnit
     */
    protected $baseUnit;

    /**
     * @var integer
     */
    protected $exponent = 1;

    /**
     * SiBaseUnitTerm constructor.
     * @param SiBaseUnit $baseUnit
     * @param int $exponent
     */
    public function __construct(SiBaseUnit $baseUnit, int $exponent = 1)
    {
        $this->baseUnit = $baseUnit;
        $this->exponent = $exponent;
    }

    /**
     * @return SiBaseUnit
     */
    public function getBaseUnit(): SiBaseUnit
    {
        return $this->baseUnit;
    }

    /**
     * @return integer
     */
    public function getExponent(): int
    {
        return $this->exponent;
    }

    /**
     * @param SiBaseUnitTerm $unitTerm
     * @return boolean
     */
    public function isCompatibleWith(SiBaseUnitTerm $unitTerm): bool
    {
        return $this->baseUnit->getSymbol() === $unitTerm->baseUnit->getSymbol()
            && $this->baseUnit->getPhysicalQuantity()->getSymbol() === $unitTerm->getBaseUnit()->getPhysicalQuantity()->getSymbol()
            && $this->exponent === $unitTerm->exponent;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $part = $this->getBaseUnit()->getSymbol();
        if ($this->getExponent() !== 1) {
            $part .= sprintf('^%d', $this->getExponent());
        }

        return $part;
    }
}
