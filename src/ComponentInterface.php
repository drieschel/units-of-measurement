<?php

namespace Drieschel\UnitsOfMeasurement;

interface ComponentInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string[]
     */
    public function getAllSymbols(): array;

    /**
     * @return string
     */
    public function getDefaultSymbol(): string;
}
