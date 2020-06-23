<?php

namespace Drieschel\UnitsOfMeasurement;

class ComponentCollectionException extends \Exception
{
    public const
        UNKNOWN_SYMBOL = 10,
        EXISTING_SYMBOL = 20;

    /**
     * @param string $symbol
     * @return ComponentCollectionException
     */
    public static function unknownSymbol(string $symbol): self
    {
        return new static (sprintf('Unknown symbol (%s)', $symbol), self::UNKNOWN_SYMBOL);
    }

    /**
     * @param string $symbol
     * @return ComponentCollectionException
     */
    public static function existingSymbol(string $symbol): self
    {
        return new static(sprintf('The symbol "%s" already exists in the collection', $symbol), self::EXISTING_SYMBOL);
    }
}
