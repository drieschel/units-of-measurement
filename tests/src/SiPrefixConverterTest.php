<?php

namespace Drieschel\UnitsOfMeasurement;

use PHPUnit\Framework\TestCase;

class SiPrefixConverterTest extends TestCase
{
    /**
     * @dataProvider convertByPrefixSymbolsProvider
     *
     * @param string $fromPrefix
     * @param string $toPrefix
     * @param float $value
     * @param float $expectedResult
     * @throws CollectionException|UnitExpressionException
     */
    public function testConvert(string $fromPrefix, string $toPrefix, float $value, float $expectedResult)
    {
        $prefixes = SiPrefixCollection::create();
        $converter = new SiPrefixConverter(null, $prefixes);
        $fromPrefix = $prefixes->get($fromPrefix);
        $toPrefix = $prefixes->get($toPrefix);
        $result = $converter->convertByPrefix($fromPrefix, $toPrefix, $value);
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @dataProvider convertByPrefixSymbolsProvider
     *
     * @param string $fromPrefixSymbol
     * @param string $toPrefixSymbol
     * @param float $value
     * @param float $result
     * @throws CollectionException
     */
    public function testConvertByPrefixSymbol(string $fromPrefixSymbol, string $toPrefixSymbol, float $value, float $result)
    {
        $converter = new SiPrefixConverter();
        $this->assertEquals($result, $converter->convertByPrefixSymbol($fromPrefixSymbol, $toPrefixSymbol, $value));
    }

    /**
     * @dataProvider convertByUnitSymbolProvider
     *
     * @param string $fromUnitSymbol
     * @param string $toPrefixSymbol
     * @param float $value
     * @param float $result
     * @throws CollectionException|ConverterException
     */
    public function testConvertByUnitSymbol(string $fromUnitSymbol, string $toPrefixSymbol, float $value, float $result)
    {
        $converter = new SiPrefixConverter();
        $this->assertEquals($result, $converter->convertByUnitSymbol($fromUnitSymbol, $toPrefixSymbol, $value));
    }

    /**
     * @dataProvider findPrefixByUnitSymbolProvider
     *
     * @param string $unitSymbol
     * @param string $expectedPrefixSymbol
     * @throws CollectionException
     * @throws ConverterException
     */
    public function testFindPrefixByUnitSymbol(string $unitSymbol, string $expectedPrefixSymbol)
    {
        $converter = new SiPrefixConverter();
        $prefix = $converter->findPrefixByUnitSymbol($unitSymbol);
        $this->assertEquals($expectedPrefixSymbol, $prefix->getDefaultSymbol());
    }

    /**
     * @dataProvider unknownUnitsDataProvider
     *
     * @param string $unitSymbol
     * @throws CollectionException
     * @throws ConverterException
     */
    public function testFindPrefixByUnitSymbolUnknownUnitSymbol(string $unitSymbol)
    {
        $this->expectException(ConverterException::class);
        $this->expectExceptionCode(ConverterException::UNKNOWN_UNIT_SYMBOL);
        (new SiPrefixConverter())->findPrefixByUnitSymbol($unitSymbol);
    }

    /**
     * @dataProvider prefixIncompatibleUnitsProvider
     *
     * @param string $unitSymbol
     * @throws CollectionException
     * @throws ConverterException
     */
    public function testFindPrefixByUnitSymbolSiPrefixIncompatibleUnit(string $unitSymbol)
    {
        $this->expectException(ConverterException::class);
        $this->expectExceptionCode(ConverterException::SI_PREFIX_INCOMPATIBLE_UNIT);
        (new SiPrefixConverter())->findPrefixByUnitSymbol($unitSymbol);
    }

    /**
     * @throws CollectionException
     * @throws ConverterException
     */
    public function testFindPrefixByUnitSymbolMissingUnitSymbol()
    {
        $this->expectException(ConverterException::class);
        $this->expectExceptionCode(ConverterException::MISSING_UNIT_SYMBOL);
        (new SiPrefixConverter())->findPrefixByUnitSymbol('');
    }

    /**
     * @dataProvider unknownPrefixesDataProvider
     *
     * @param string $unitSymbolWithUnknownPrefix
     * @throws CollectionException
     * @throws ConverterException
     */
    public function testFindPrefixByUnitSymbolUnknownPrefixSymbol(string $unitSymbolWithUnknownPrefix)
    {
        $this->expectException(ConverterException::class);
        $this->expectExceptionCode(ConverterException::UNKNOWN_PREFIX_SYMBOL);
        (new SiPrefixConverter())->findPrefixByUnitSymbol($unitSymbolWithUnknownPrefix);
    }

    /**
     * @dataProvider conversionExponentProvider
     *
     * @param int $fromExponent
     * @param int $toExponent
     * @param $expectedExponent
     */
    public function testDetermineConversionExponent(int $fromExponent, int $toExponent, $expectedExponent)
    {
        $actualExponent = (new SiPrefixConverter())->determineConversionExponent($fromExponent, $toExponent);
        return $this->assertEquals($expectedExponent, $actualExponent);
    }

    /**
     * @return array|array[]
     */
    public function convertByPrefixSymbolsProvider(): array
    {
        return [
            //From prefix | To prefix | Value | Result
            ['', 'c', 235, 23500],
            ['m', '', 123, 0.123],
            ['', 'k', 32, 0.032],
            ['G', 'k', 22, 22000000],
            ['G', 'G', 23, 23],
            ['µ', 'T', 42, 0.000000000000000042],
            ['p', 'c', 77, 0.0000000077],
            ['k', 'm', 7, 7000000],
            ['m', 'k', 2, 0.000002],
            ['', '', 65, 65],
        ];
    }

    /**
     * @return mixed[]
     */
    public function convertByUnitSymbolProvider(): array
    {
        return [
            //From prefix | To prefix | Value | Result
            ['kg', 'm', 3.12, 3.12E6],
            ['nt', '', 1.23E9, 1.23],
            ['dal', 'µ', 42, 42E7],
            ['dam', 'k', 0.31, 31E-4],
            ['cm', 'da', 6, 6E-3],
            ['g', 'm', 4.1, 4.1E3],
            ['L', 'd', 99.3, 993],
            ['dm²', 'da', 12, 12E-4],
            ['m³', 'm', 3, 3E9],
            ['m³', 'k', 3E9, 3],
        ];
    }

    /**
     * @return string[]
     */
    public function findPrefixByUnitSymbolProvider(): array
    {
        return [
            ['mL', 'm'],
            ['dl', 'd'],
            ['kg', 'k'],
            ['GA', 'G'],
            ['cm', 'c'],
            ['Mt', 'M'],
            //['cm3', 'c'],
            ['m', ''],
            ['dal', 'da'],
            ['dam', 'da'],
        ];
    }

    /**
     * @return string[]
     */
    public function unknownUnitsDataProvider(): array
    {
        return [
            ['_'],
            ['maaaa'],
            ['a'],
            ['ma'],
        ];
    }

    public function prefixIncompatibleUnitsProvider(): array
    {
        return [
            ['min'],
            ['kpt'],
        ];
    }

    /**
     * @return string[]
     */
    public function unknownPrefixesDataProvider(): array
    {
        return [
            ['Lm'],
            ['iL'],
            ['xg'],
            ['qA'],
        ];
    }

    /**
     * @return integer[]
     */
    public function conversionExponentProvider(): array
    {
        return [
            [0, 0, 0],
            [1, 1, 0],
            [-1, -1, 0],
            [0, -1, 1],
            [0, 1, -1],
            [-1, 1, -2],
            [1, -1, 2],
        ];
    }
}
