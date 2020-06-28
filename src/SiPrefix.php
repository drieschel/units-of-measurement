<?php


namespace Drieschel\UnitsOfMeasurement;

class SiPrefix extends AbstractComponent
{
    /**
     * @var float
     */
    protected $factor;

    /**
     * SiPrefix constructor.
     * @param string $name
     * @param string $defaultSymbol
     * @param float $factor
     */
    public function __construct(string $name, string $defaultSymbol, float $factor)
    {
        parent::__construct($name, $defaultSymbol);
        $this->factor = $factor;
    }

    /**
     * @return float
     */
    public function getFactor(): float
    {
        return $this->factor;
    }
}
