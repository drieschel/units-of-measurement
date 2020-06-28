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
     * @var string[]
     */
    protected $symbols;

    /**
     * @var string
     */
    protected $dimension;

    /**
     * @var bool
     */
    protected $isBaseQuantity = false;

    /**
     * PhysicalQuantity constructor.
     * @param string $name
     * @param string $defaultSymbol
     * @param string $dimension
     * @param bool $isBaseQuantity
     */
    public function __construct(string $name, string $defaultSymbol, string $dimension, bool $isBaseQuantity = false)
    {
        $this->name = $name;
        $this->symbols[0] = $defaultSymbol;
        $this->dimension = $dimension;
        $this->isBaseQuantity = $isBaseQuantity;
    }

    /**
     * @param string ...$symbols
     * @return PhysicalQuantity
     */
    public function addSymbols(string ...$symbols): self
    {
        foreach ($symbols as $symbol) {
            if (!in_array($symbol, $this->symbols, true)) {
                $this->symbols[] = $symbol;
            }
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string[]
     */
    public function getAllSymbols(): array
    {
        return $this->symbols;
    }

    /**
     * @return string
     */
    public function getDefaultSymbol(): string
    {
        return $this->symbols[0];
    }

    /**
     * @return string
     */
    public function getDimension(): string
    {
        return $this->dimension;
    }

    /**
     * @return bool
     */
    public function isBaseQuantity(): bool
    {
        return $this->isBaseQuantity;
    }
}
