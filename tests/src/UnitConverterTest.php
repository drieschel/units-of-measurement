<?php

namespace Drieschel\UnitsOfMeasurement;

use PHPUnit\Framework\TestCase;

class UnitConverterTest extends TestCase
{
    /**
     * @dataProvider convertUnitsProvider
     *
     * @param string $fromSymbol
     * @param string $toSymbol
     * @param float $value
     * @param float $expectedResult
     * @throws CollectionException
     * @throws ConverterException|UnitExpressionException
     */
    public function testConvert(string $fromSymbol, string $toSymbol, float $value, float $expectedResult)
    {
        $units = UnitCollection::createAllUnits();
        $converter = new UnitConverter($units);
        $actual = $converter->convert($units->get($fromSymbol), $units->get($toSymbol), $value);
        $this->assertEquals($expectedResult, $actual);
    }

    /**
     * @dataProvider convertUnitsProvider
     *
     * @param string $fromSymbol
     * @param string $toSymbol
     * @param float $value
     * @param float $expectedResult
     * @throws CollectionException
     * @throws ConverterException
     */
    public function testConvertBySymbol(string $fromSymbol, string $toSymbol, float $value, float $expectedResult)
    {
        $converter = new UnitConverter();
        $actual = $converter->convertBySymbol($fromSymbol, $toSymbol, $value);
        $this->assertEquals($expectedResult, $actual);
    }

    /**
     * @dataProvider conversionFactorProvider
     *
     * @param string $unitSymbol
     * @param float $expectedFactor
     * @throws \ReflectionException
     */
    public function testGetConversionFactorBySymbol(string $unitSymbol, float $expectedFactor)
    {
        $converter = new UnitConverter();
        $reflClass = new \ReflectionClass($converter);
        $reflMethod = $reflClass->getMethod('getConversionFactorBySymbol');
        $reflMethod->setAccessible(true);

        $actualFactor = $reflMethod->invoke($converter, $unitSymbol);
        $this->assertEquals($expectedFactor, $actualFactor);
    }

    /**
     * @dataProvider findUnitBySymbol
     *
     * @param string $unitSymbol
     * @param string $unitName
     * @throws CollectionException
     * @throws ConverterException
     */
    public function testFindUnitBySymbol(string $unitSymbol, string $unitName)
    {
        $converter = new UnitConverter();

        /** @var Unit $unit */
        $unit = $converter->findUnitBySymbol($unitSymbol);
        $this->assertEquals($unitName, $unit->getName());
    }

    public function testFindUnitBySymbolUnknownUnitSymbol()
    {
        $this->expectException(ConverterException::class);
        $this->expectExceptionCode(ConverterException::UNKNOWN_UNIT_SYMBOL);
        $converter = new UnitConverter();

        /** @var Unit $unit */
        $converter->findUnitBySymbol('dasdas');
    }

    public function testFindUnitBySymbolUnknownPrefixSymbol()
    {
        $this->expectException(ConverterException::class);
        $this->expectExceptionCode(ConverterException::UNKNOWN_PREFIX_SYMBOL);
        $converter = new UnitConverter();

        /** @var Unit $unit */
        $converter->findUnitBySymbol('Lm');
    }

    /**
     * @return array|array[]
     */
    public function convertUnitsProvider(): array
    {
        return [
            ['US gal', 'L', 5.1, 19.3056000984],
            ['gal', 'L', 9.423, 42.83780607],
            ['gal', 'US gal', 2.33, 2.798213326426312],
            ['ft³', 'in³', 1., 1728.],
            ['yd³', 'ft³', 1., 27.],
            ['m', 'in', 23.55E-3, 0.92716535433071],
            ['mi', 'm', 12.5, 20.1168E3],
        ];
    }

    /**
     * @return array
     */
    public function conversionFactorProvider(): array
    {
        return [
            ['mL', 1E-3],
            ['m', 1E0],
            ['Tm', 1E12],
            ['nL', 1E-9],
            ['km', 1E3],
            ['daL', 1E1],
        ];
    }

    /**
     * @return string[]
     */
    public function findUnitBySymbol(): array
    {
        return [
            ['Tt', 'tonne'],
            ['in', 'inch'],
            ['cu yd', 'cubic yard'],
            ['l', 'litre'],
            ['L', 'litre'],
            ['dL', 'litre'],
            ['kg', 'kilogram'],
            ['g', 'gram'],
            ['mg', 'gram'],
            ['mm', 'metre'],
            ['m', 'metre'],
        ];
    }
}
