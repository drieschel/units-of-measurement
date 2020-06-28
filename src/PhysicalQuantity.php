<?php

namespace Drieschel\UnitsOfMeasurement;

/**
 * Class PhysicalQuantity
 * @package Drieschel\UnitsOfMeasurement
 *
 * @method Unit get(string $symbol)
 */
class PhysicalQuantity extends ComponentCollection implements ComponentInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $symbol;

    /**
     * @var string
     */
    protected $dimensionSymbol;

    /**
     * @var bool
     */
    protected $isBaseQuantity = false;

    /**
     * PhysicalQuantity constructor.
     * @param string $name
     * @param string $symbol
     * @param string $dimensionSymbol
     * @param bool $isBaseQuantity
     */
    public function __construct(string $name, string $symbol, string $dimensionSymbol, bool $isBaseQuantity = false)
    {
        $this->name = $name;
        $this->symbol = $symbol;
        $this->dimensionSymbol = $dimensionSymbol;
        $this->isBaseQuantity = $isBaseQuantity;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->symbol;
    }

    /**
     * @return string
     */
    public function getDimensionSymbol(): string
    {
        return $this->dimensionSymbol;
    }

    /**
     * @return bool
     */
    public function isBaseQuantity(): bool
    {
        return $this->isBaseQuantity;
    }
}
