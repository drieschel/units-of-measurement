<?php


namespace Drieschel\UnitsOfMeasurement;

class SiPrefix extends AbstractComponent
{
    /**
     * @var int
     */
    protected $exponent;

    /**
     * SiPrefix constructor.
     * @param string $name
     * @param string $defaultSymbol
     * @param int $exponent
     */
    public function __construct(string $name, string $defaultSymbol, int $exponent)
    {
        parent::__construct($name, $defaultSymbol);
        $this->exponent = $exponent;
    }

    /**
     * @return int
     */
    public function getExponent(): int
    {
        return $this->exponent;
    }

    /**
     * @return float
     */
    public function getFactor(): float
    {
        return pow(10, $this->exponent);
    }
}
