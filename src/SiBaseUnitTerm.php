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
        $symbolFound = false;
        foreach ($this->getBaseUnit()->getAllSymbols() as $thisSymbol) {
            foreach ($unitTerm->getBaseUnit()->getAllSymbols() as $thatSymbol) {
                if ($thisSymbol === $thatSymbol) {
                    $symbolFound = true;
                    break;
                }
            }
        }

        return $symbolFound
            && $this->baseUnit->getPhysicalQuantity()->getDefaultSymbol() === $unitTerm->getBaseUnit()->getPhysicalQuantity()->getDefaultSymbol()
            && $this->exponent === $unitTerm->exponent;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $part = $this->getBaseUnit()->getDefaultSymbol();
        if ($this->getExponent() !== 1) {
            $part .= sprintf('^%d', $this->getExponent());
        }

        return $part;
    }
}
