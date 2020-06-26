<?php

namespace Drieschel\UnitsOfMeasurement;

class CollectionException extends \Exception
{
    public const
        SYMBOL_UNKNOWN = 10,
        SYMBOL_EXISTS = 20,
        CLASS_IS_NOT_A_UNIT = 30;

    /**
     * @param string $symbol
     * @return CollectionException
     */
    public static function symbolUnknown(string $symbol): self
    {
        return new static (sprintf('Unknown symbol (%s)', $symbol), self::SYMBOL_UNKNOWN);
    }

    /**
     * @param string $symbol
     * @return CollectionException
     */
    public static function symbolExists(string $symbol): self
    {
        return new static(sprintf('The symbol "%s" already exists in the collection', $symbol), self::SYMBOL_EXISTS);
    }

    /**
     * @param string $className
     * @return static
     */
    public static function classIsNotAUnit(string $className): self
    {
        return new static(sprintf('The class "%s" does not inherit from "%s"', $className, Unit::class), self::CLASS_IS_NOT_A_UNIT);
    }
}
