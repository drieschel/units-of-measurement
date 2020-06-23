<?php

namespace Drieschel\UnitsOfMeasurement;

class UnitConverter
{
    /**
     * @var SiPrefixConverter
     */
    protected $prefixConverter;

    /**
     * @var SiPrefixCollection
     */
    protected $prefixes;

    /**
     * @var UnitCollection
     */
    protected $units;

    /**
     * UnitConverter constructor.
     * @param UnitCollection|null $units
     * @param SiPrefixCollection|null $prefixes
     * @throws ComponentCollectionException
     */
    public function __construct(UnitCollection $units = null, SiPrefixCollection $prefixes = null)
    {
        if(is_null($prefixes)) {
            $prefixes = SiPrefixCollection::create();
        }

        if(is_null($units)) {
            $units = UnitCollection::create();
        }

        $this->prefixConverter = new SiPrefixConverter($units, $prefixes);
        $this->prefixes = $prefixes;
        $this->units = $units;
    }

    /**
     * @param Unit $fromUnit
     * @param Unit $toUnit
     * @param float $value
     * @return float
     * @throws ConverterException
     */
    public function convert(Unit $fromUnit, Unit $toUnit, float $value): float
    {
        if (!$fromUnit->getUnitExpression()->isCompatibleWith($toUnit->getUnitExpression())) {
            throw ConverterException::incompatibleUnits($fromUnit, $toUnit);
        }

        return $fromUnit->getUnitExpression()->getFactor() * $value / $toUnit->getUnitExpression()->getFactor();
    }

    /**
     * @param string $fromUnitSymbol
     * @param string $toUnitSymbol
     * @param float $value
     * @return float
     * @throws ComponentCollectionException
     * @throws ConverterException
     * @throws \Exception
     */
    public function convertBySymbol(string $fromUnitSymbol, string $toUnitSymbol, float $value): float
    {
        $fromUnit = $this->findUnitBySymbol($fromUnitSymbol);
        $toUnit = $this->findUnitBySymbol($toUnitSymbol);

        if (!$fromUnit->getUnitExpression()->isCompatibleWith($toUnit->getUnitExpression())) {
            throw ConverterException::incompatibleUnits($fromUnit, $toUnit);
        }

        return $this->getConversionFactorBySymbol($fromUnitSymbol) * $value / $this->getConversionFactorBySymbol($toUnitSymbol);
    }

    /**
     * @param string $unitSymbol
     * @return float
     * @throws \Exception
     */
    protected function getConversionFactorBySymbol(string $unitSymbol): float
    {
        $unit = $this->findUnitBySymbol($unitSymbol);
        $prefix = $this->prefixConverter->findPrefixByUnitSymbol($unitSymbol);
        $factor = $unit->getUnitExpression()->getFactor();
        if (!$this->units->has($unitSymbol)) {
            $factor = $prefix->getFactor();
        }

        return $factor;
    }

    /**
     * @param string $unitSymbol
     * @return Unit
     * @throws ComponentCollectionException
     * @throws ConverterException
     */
    public function findUnitBySymbol(string $unitSymbol): Unit
    {
        $this->prefixConverter->findPrefixByUnitSymbol($unitSymbol);
        $unitSymbolLength = strlen($unitSymbol);

        for($i = 0; $i < $unitSymbolLength && $i < 2; $i++) {
            if($this->units->has(substr($unitSymbol, $i))) {
                return $this->units->get(substr($unitSymbol, $i));
            }
        }

        throw ConverterException::unknownUnitSymbol($unitSymbol);
    }
}
