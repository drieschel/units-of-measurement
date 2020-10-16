<?php

namespace Drieschel\UnitsOfMeasurement;

class SiPrefixConverter
{
    /**
     * @var SiPrefixCollection
     */
    protected $prefixes;

    /**
     * @var UnitCollection
     */
    protected $units;

    /**
     * @var UnitConverter
     */
    protected $unitConverter;

    /**
     * SiPrefixConverter constructor.
     * @param UnitCollection|null $units
     * @param SiPrefixCollection|null $prefixes
     * @param UnitConverter|null $unitConverter
     * @throws CollectionException
     * @throws UnitExpressionException
     */
    public function __construct(UnitCollection $units = null, SiPrefixCollection $prefixes = null, UnitConverter $unitConverter = null)
    {
        if (is_null($prefixes)) {
            $prefixes = SiPrefixCollection::create();
        }

        if (is_null($units)) {
            $units = UnitCollection::createAllUnits();
        }

        if (is_null($unitConverter)) {
            $unitConverter = new UnitConverter($units, $prefixes, $this);
        }

        $this->unitConverter = $unitConverter;
        $this->prefixes = $prefixes;
        $this->units = $units;
    }

    /**
     * @param SiPrefix $fromPrefix
     * @param SiPrefix $toPrefix
     * @param float $value
     * @return float
     */
    public function convertByPrefix(SiPrefix $fromPrefix, SiPrefix $toPrefix, float $value): float
    {
        $exponent = $this->determineConversionExponent($fromPrefix->getExponent(), $toPrefix->getExponent());

        return $value * pow(10, $exponent);
    }

    /**
     * @param string $fromPrefixSymbol
     * @param string $toPrefixSymbol
     * @param float $value
     * @return float
     * @throws CollectionException
     */
    public function convertByPrefixSymbol(string $fromPrefixSymbol, string $toPrefixSymbol, float $value = 1.): float
    {
        return $this->convertByPrefix($this->prefixes->get($fromPrefixSymbol), $this->prefixes->get($toPrefixSymbol), $value);
    }

    /**
     * @param string $fromUnitSymbol
     * @param string $toPrefixSymbol
     * @param float $value
     * @return float
     * @throws CollectionException
     * @throws ConverterException
     */
    public function convertByUnitSymbol(string $fromUnitSymbol, string $toPrefixSymbol, float $value = 1.): float
    {
        $unit = $this->unitConverter->findUnitBySymbol($fromUnitSymbol);
        $fromPrefix = $this->findPrefixByUnitSymbol($fromUnitSymbol);
        $toPrefix = $this->prefixes->get($toPrefixSymbol);

        if ($unit instanceof NonSiUnit && $unit->isAcceptedForUseWithSi()) {
            return $this->convertByPrefix($fromPrefix, $toPrefix, $value);
        }

        $dimension = 0;
        foreach ($unit->getUnitExpression()->getTerms() as $term) {
            $dimension += $term->getExponent();
        }

        $exponent = $this->determineConversionExponent($fromPrefix->getExponent(), $toPrefix->getExponent());

        return $value * pow(10, ($exponent * $dimension));
    }

    /**
     * @param float $fromExponent
     * @param float $toExponent
     * @return float
     */
    public function determineConversionExponent(float $fromExponent, float $toExponent): float
    {
        return $fromExponent - $toExponent;
    }

    /**
     * @param string $unitSymbol
     * @return SiPrefix
     * @throws CollectionException
     * @throws ConverterException
     */
    public function findPrefixByUnitSymbol(string $unitSymbol): SiPrefix
    {
        $unitSymbolLength = strlen($unitSymbol);

        if ($unitSymbolLength === 0) {
            throw ConverterException::missingUnitSymbol();
        }

        $unit = null;
        $prefixSymbol = null;
        for ($i = 0; $i < $unitSymbolLength && $i < 3; $i++) {
            if ($this->units->has(substr($unitSymbol, $i))) {
                $unit = $this->units->get(substr($unitSymbol, $i));
                $prefixSymbol = substr($unitSymbol, 0, $i);
                break;
            }
        }

        if (is_null($unit)) {
            throw ConverterException::unknownUnitSymbol($unitSymbol);
        }

        if ($prefixSymbol !== '' && !$unit->isSiPrefixCompatible()) {
            throw ConverterException::siPrefixIncompatibleUnit($unit->getDefaultSymbol());
        }

        if ($prefixSymbol === '' && $unit->isSiPrefixCompatible() && strlen($unit->getDefaultSymbol()) > 1) {
            for ($i = 0; $i < $unitSymbolLength; $i++) {
                $tPrefixSymbol = substr($unitSymbol, 0, $i + 1);
                $tUnitSymbol = substr($unitSymbol, $i + 1);
                if ($this->prefixes->has($tPrefixSymbol) && $this->units->has($tUnitSymbol)) {
                    return $this->prefixes->get($tPrefixSymbol);
                }
            }
        }

        if (!$this->prefixes->has($prefixSymbol)) {
            throw ConverterException::unknownPrefixSymbol($prefixSymbol);
        }

        return $this->prefixes->get($prefixSymbol);
    }

    /**
     * @return SiPrefixCollection
     */
    public function getPrefixes(): SiPrefixCollection
    {
        return $this->prefixes;
    }

    /**
     * @return UnitCollection
     */
    public function getUnits(): UnitCollection
    {
        return $this->units;
    }
}
