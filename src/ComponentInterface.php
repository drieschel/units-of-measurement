<?php

namespace Drieschel\UnitsOfMeasurement;

interface ComponentInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getSymbol(): string;
}
