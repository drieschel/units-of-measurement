<?php
namespace Drieschel\UnitsOfMeasurement;

class SiPrefixCollection extends ComponentCollection
{
    /**
     * @param string $symbol
     * @return float
     * @throws CollectionException
     */
    public function getFactor(string $symbol): float
    {
        if (!$this->has($symbol)) {
            throw CollectionException::symbolUnknown($symbol);
        }
        return $this->get($symbol)->getFactor();
    }

    /**
     * @return SiPrefixCollection
     * @throws CollectionException
     */
    public static function create(): self
    {
        return (new static())
            ->set(new SiPrefix('yotta', 'Y', 1E24))
            ->set(new SiPrefix('zetta', 'Z', 1E21))
            ->set(new SiPrefix('exa', 'E', 1E18))
            ->set(new SiPrefix('peta', 'P', 1E15))
            ->set(new SiPrefix('tera', 'T', 1E12))
            ->set(new SiPrefix('giga', 'G', 1E9))
            ->set(new SiPrefix('mega', 'M', 1E6))
            ->set(new SiPrefix('kilo', 'k', 1E3))
            ->set(new SiPrefix('hecto', 'h', 1E2))
            ->set(new SiPrefix('deka', 'da', 1E1))
            ->set(new SiPrefix('', '', 1E0))
            ->set(new SiPrefix('deci', 'd', 1E-1))
            ->set(new SiPrefix('centi', 'c', 1E-2))
            ->set(new SiPrefix('milli', 'm', 1E-3))
            ->set(new SiPrefix('micro', 'µ', 1E-6))
            ->set(new SiPrefix('nano', 'n', 1E-9))
            ->set(new SiPrefix('pic', 'p', 1E-12))
            ->set(new SiPrefix('femto', 'f', 1E-15))
            ->set(new SiPrefix('atto', 'a', 1E-18))
            ->set(new SiPrefix('zepto', 'z', 1E-21))
            ->set(new SiPrefix('yocto', 'y', 1E-24))
        ;
    }
}
