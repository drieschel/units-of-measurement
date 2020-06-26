<?php

namespace Drieschel\UnitsOfMeasurement;

class AbstractComponent implements ComponentInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string[]
     */
    protected $symbols = [];

    /**
     * AbstractComponent constructor.
     * @param string $name
     * @param string $symbol
     */
    public function __construct(string $name, string $symbol)
    {
        $this->name = $name;
        $this->symbols[] = $symbol;
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
        return $this->symbols[0];
    }

    /**
     * @param string ...$symbols
     * @return self
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
     * @return string[]
     */
    public function getSymbols(): array
    {
        return $this->symbols;
    }
}
