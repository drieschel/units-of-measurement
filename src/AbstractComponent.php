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
     * @param string $defaultSymbol
     */
    public function __construct(string $name, string $defaultSymbol)
    {
        $this->name = $name;
        $this->symbols[0] = $defaultSymbol;
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
    public function getDefaultSymbol(): string
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
    public function getAllSymbols(): array
    {
        return $this->symbols;
    }
}
