<?php
namespace Drieschel\UnitsOfMeasurement;

class UnitExpressionException extends \Exception
{
    public const
        INVALID_FACTOR = 10;

    /**
     * @param float $factor
     * @return UnitExpressionException
     */
    public static function invalidFactor(float $factor): self
    {
        return new static(sprintf('%f is not a valid factor', $factor), self::INVALID_FACTOR);
    }
}