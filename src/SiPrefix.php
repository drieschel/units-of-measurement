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
     * @param string $symbol
     * @param float $factor
     */
    public function __construct(string $name, string $symbol, float $factor)
    {
        parent::__construct($name, $symbol);
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
