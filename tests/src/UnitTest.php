<?php

namespace Drieschel\UnitsOfMeasurement;

use PHPUnit\Framework\TestCase;

class UnitTest extends TestCase
{
    /**
     * @dataProvider convertingProvider
     *
     * @param float $factor
     * @param $value
     * @param $convertedValue
     * @throws UnitExpressionException
     */
    public function testConvertToSi(float $factor, $value, $convertedValue)
    {
        $physicalQuantity = new PhysicalQuantity('yo', 'lo', 'symbolix', true);
        $expression = new UnitExpression($factor, new SiBaseUnitTerm(new SiBaseUnit('foo', 'bae', $physicalQuantity)));
        $unit = new Unit('eg', 'al', $expression, $physicalQuantity, mt_rand(0, 1) === 1);
        $this->assertEquals($convertedValue, $unit->convertToSi($value));
    }

    /**
     * @dataProvider convertingProvider
     *
     * @param float $factor
     * @param $expectedValue
     * @param $convertedValue
     * @throws UnitExpressionException
     */
    public function testConvertFromSi(float $factor, $expectedValue, $convertedValue)
    {
        $physicalQuantity = new PhysicalQuantity('yo', 'lo', 'symbolix', true);
        $expression = new UnitExpression($factor, new SiBaseUnitTerm(new SiBaseUnit('foo', 'bae', $physicalQuantity)));
        $unit = new Unit('eg', 'al', $expression, $physicalQuantity, mt_rand(0, 1) === 1);
        $this->assertEquals($expectedValue, $unit->convertFromSi($convertedValue));
    }

    public function convertingProvider(): array
    {
        return [
            [-1., 1., -1.],
            [1., -2., -2.],
            [1., 2., 2.],
            [1.1, 0.1, 0.11],
            [0.1, 1.1, 0.11],
            [0.1, 0.1, 0.01],
        ];
    }
}
