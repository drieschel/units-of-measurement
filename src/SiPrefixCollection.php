<?php
namespace Drieschel\UnitsOfMeasurement;

/**
 * Class SiPrefixCollection
 * @package Drieschel\UnitsOfMeasurement
 *
 * @method SiPrefix get(string $symbol) : ComponentInterface
 */
class SiPrefixCollection extends ComponentCollection
{
    /**
     * @param string $prefixSymbol
     * @return float
     * @throws CollectionException
     */
    public function getFactor(string $prefixSymbol): float
    {
        if (!$this->has($prefixSymbol)) {
            throw CollectionException::symbolUnknown($prefixSymbol);
        }
        return $this->get($prefixSymbol)->getFactor();
    }

    /**
     * @return SiPrefixCollection
     * @throws CollectionException
     */
    public static function create(): self
    {
        return (new static())
            ->set(new SiPrefix('yotta', 'Y', 24))
            ->set(new SiPrefix('zetta', 'Z', 21))
            ->set(new SiPrefix('exa', 'E', 18))
            ->set(new SiPrefix('peta', 'P', 15))
            ->set(new SiPrefix('tera', 'T', 12))
            ->set(new SiPrefix('giga', 'G', 9))
            ->set(new SiPrefix('mega', 'M', 6))
            ->set(new SiPrefix('kilo', 'k', 3))
            ->set(new SiPrefix('hecto', 'h', 2))
            ->set(new SiPrefix('deka', 'da', 1))
            ->set(new SiPrefix('', '', 0))
            ->set(new SiPrefix('deci', 'd', -1))
            ->set(new SiPrefix('centi', 'c', -2))
            ->set(new SiPrefix('milli', 'm', -3))
            ->set(new SiPrefix('micro', 'µ', -6))
            ->set(new SiPrefix('nano', 'n', -9))
            ->set(new SiPrefix('pic', 'p', -12))
            ->set(new SiPrefix('femto', 'f', -15))
            ->set(new SiPrefix('atto', 'a', -18))
            ->set(new SiPrefix('zepto', 'z', -21))
            ->set(new SiPrefix('yocto', 'y', -24))
        ;
    }
}
