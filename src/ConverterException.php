<?php

namespace Drieschel\UnitsOfMeasurement;

class ConverterException extends \Exception
{
    public const
        MISSING_UNIT_SYMBOL = 10,
        UNKNOWN_UNIT_SYMBOL = 20,
        UNKNOWN_PREFIX_SYMBOL = 30,
        INCOMPATIBLE_UNITS = 40,
        SI_PREFIX_INCOMPATIBLE_UNIT = 50;

    public static function missingUnitSymbol(): self
    {
        return new static('Unit symbol can not be an empty string', self::MISSING_UNIT_SYMBOL);
    }

    /**
     * @param string $unitSymbol
     * @return ConverterException
     */
    public static function unknownUnitSymbol(string $unitSymbol): self
    {
        return new static(sprintf('"%s" is an unknown (SI prefixed) unit symbol', $unitSymbol), self::UNKNOWN_UNIT_SYMBOL);
    }

    /**
     * @param string $prefixSymbol
     * @return ConverterException
     */
    public static function unknownPrefixSymbol(string $prefixSymbol): self
    {
        return new static(sprintf('"%s" is an unknown SI prefix symbol', $prefixSymbol), self::UNKNOWN_PREFIX_SYMBOL);
    }

    /**
     * @param Unit $fromUnit
     * @param Unit $toUnit
     * @return ConverterException
     */
    public static function incompatibleUnits(Unit $fromUnit, Unit $toUnit): self
    {
        return new static(sprintf('Unit "%s [%s]" can not converted into "%s [%s]"', $fromUnit->getName(), $fromUnit->getDefaultSymbol(), $toUnit->getName(), $toUnit->getDefaultSymbol()), self::INCOMPATIBLE_UNITS);
    }

    /**
     * @param string $unitSymbol
     * @return ConverterException
     */
    public static function siPrefixIncompatibleUnit(string $unitSymbol): self
    {
        return new static(sprintf('Unit "%s" is not compatible for use with SI prefixes', $unitSymbol), self::SI_PREFIX_INCOMPATIBLE_UNIT);
    }
}
