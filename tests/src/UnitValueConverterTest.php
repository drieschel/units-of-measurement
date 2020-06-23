<?php

namespace Drieschel\UnitsOfMeasurement;

use PHPUnit\Framework\TestCase;

class UnitValueConverterTest extends TestCase
{
    public function testConvertToSi()
    {
    }

    public function testConvertToNonSi()
    {
    }

    /**
     * @dataProvider convertUnitsProvider
     *
     * @param string $fromSymbol
     * @param string $toSymbol
     * @param float $value
     * @param float $expectedResult
     * @throws ComponentCollectionException
     * @throws ConverterException
     */
    public function testConvert(string $fromSymbol, string $toSymbol, float $value, float $expectedResult)
    {
        $units = UnitCollection::create();
        $converter = new UnitConverter($units);
        $actual = $converter->convert($units->get($fromSymbol), $units->get($toSymbol), $value);
        $this->assertEquals($expectedResult, $actual);
    }

    public function testDerivedUnitConversion()
    {
        $units = UnitCollection::create();
        $mm = $units->get('mm');
    }

    public function convertUnitsProvider(): array
    {
        return [
            ['gal', 'L', 5.1, 19.3056000984],
            ['ft³', 'in³', 1, 1728],
            ['yd³', 'ft³', 1, 27],
        ];
    }
}
