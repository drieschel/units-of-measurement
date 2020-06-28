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
     * SiPrefixValueConverter constructor.
     * @param UnitCollection|null $units
     * @param SiPrefixCollection|null $prefixes
     * @throws CollectionException
     */
    public function __construct(UnitCollection $units = null, SiPrefixCollection $prefixes = null)
    {
        if (is_null($prefixes)) {
            $prefixes = SiPrefixCollection::create();
        }

        if (is_null($units)) {
            $units = UnitCollection::createAllUnits();
        }

        $this->prefixes = $prefixes;
        $this->units = $units;
    }

    /**
     * @param SiPrefix $fromPrefix
     * @param SiPrefix $toPrefix
     * @param float $value
     * @return float
     */
    public function convert(SiPrefix $fromPrefix, SiPrefix $toPrefix, float $value): float
    {
        $fromFactor = $fromPrefix->getFactor();
        $toFactor = $toPrefix->getFactor();

        if ($fromFactor < 0. && $toFactor > 0.) {
            return $value / $fromFactor;
        } elseif ($fromFactor > 0. && $toFactor < 0.) {
            return $value * $fromFactor;
        }

        return $fromFactor / $toFactor * $value;
    }

    /**
     * @param string $fromPrefixSymbol
     * @param string $toPrefixSymbol
     * @param float $value
     * @return float
     * @throws CollectionException
     */
    public function convertByPrefixSymbol(string $fromPrefixSymbol, string $toPrefixSymbol, float $value): float
    {
        return $this->convert($this->prefixes->get($fromPrefixSymbol), $this->prefixes->get($toPrefixSymbol), $value);
    }

    /**
     * @param string $fromUnitSymbol
     * @param string $toPrefixSymbol
     * @param float $value
     * @return float
     * @throws CollectionException
     * @throws ConverterException
     */
    public function convertByUnitSymbol(string $fromUnitSymbol, string $toPrefixSymbol, float $value): float
    {
        return $this->convert($this->findPrefixByUnitSymbol($fromUnitSymbol), $this->prefixes->get($toPrefixSymbol), $value);
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

        if($prefixSymbol !== '' && !$unit->isSiPrefixCompatible()) {
            throw ConverterException::siPrefixIncompatibleUnit($unit->getDefaultSymbol());
        }

        if ($prefixSymbol === '' && $unit->isSiPrefixCompatible() && strlen($unit->getDefaultSymbol()) > 1) {
            for ($i = 0; $i < $unitSymbolLength; $i++) {
                $tPrefixSymbol = substr($unitSymbol, 0, $i + 1);
                if ($this->prefixes->has($tPrefixSymbol)) {
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
