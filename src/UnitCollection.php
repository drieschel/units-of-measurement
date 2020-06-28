<?php

namespace Drieschel\UnitsOfMeasurement;

/**
 * Class UnitCollection
 * @package Drieschel\UnitsOfMeasure
 *
 * @method Unit get(string $symbol)
 */
class UnitCollection extends ComponentCollection
{
    /**
     * @return UnitCollection
     * @throws CollectionException
     */
    public function getNonSiUnits(): self
    {
        return $this->filterUnitsByType(NonSiUnit::class);
    }

    /**
     * @return UnitCollection
     * @throws CollectionException
     */
    public function getSiBaseUnits(): self
    {
        return $this->filterUnitsByType(SiBaseUnit::class);
    }

    /**
     * @return UnitCollection
     * @throws CollectionException
     */
    public function getSiDerivedUnits(): self
    {
        return $this->filterUnitsByType(SiDerivedUnit::class);
    }

    /**
     * @return UnitCollection
     * @throws CollectionException
     */
    public function getSiPrefixCompatibleUnits(): self
    {
        return $this->filterByClosure(function (Unit $unit) {
            return $unit->isSiPrefixCompatible();
        });
    }

    /**
     * @param PhysicalQuantityCollection|null $physicalQuantities
     * @param bool $preferUsc
     * @return static
     * @throws CollectionException
     * @throws UnitExpressionException
     */
    public static function createAllUnits(PhysicalQuantityCollection $physicalQuantities = null, bool $preferUsc = false): self
    {
        if (is_null($physicalQuantities)) {
            $physicalQuantities = PhysicalQuantityCollection::create();
        }

        $self = static::createSiBaseUnits($physicalQuantities);

        $self
            ->merge(static::createSiDerivedUnits($physicalQuantities, $self))
            ->merge(static::createNonSiUnits($physicalQuantities, $self, $preferUsc));

        return $self;
    }

    /**
     * @param PhysicalQuantityCollection $physicalQuantities
     * @return static
     * @throws CollectionException
     */
    public static function createSiBaseUnits(PhysicalQuantityCollection $physicalQuantities): self
    {
        return (new static())
            ->set(new SiBaseUnit('metre', 'm', $physicalQuantities->get('l')))
            ->set(new SiBaseUnit('kilogram', 'kg', $physicalQuantities->get('m')))
            ->set(new SiBaseUnit('second', 's', $physicalQuantities->get('t')))
            ->set(new SiBaseUnit('ampere', 'A', $physicalQuantities->get('I')))
            ->set(new SiBaseUnit('kelvin', 'K', $physicalQuantities->get('T')))
            ->set(new SiBaseUnit('mole', 'mol', $physicalQuantities->get('n')))
            ->set(new SiBaseUnit('candela', 'cd', $physicalQuantities->get('L')));
    }

    /**
     * @param PhysicalQuantityCollection $physicalQuantities
     * @param UnitCollection $siBaseUnits
     * @return UnitCollection
     * @throws CollectionException
     * @throws UnitExpressionException
     */
    public static function createSiDerivedUnits(PhysicalQuantityCollection $physicalQuantities, UnitCollection $siBaseUnits): self
    {
        return (new static())
            ->set(new SiDerivedUnit('radian', 'rad', new UnitExpression(1., new SiBaseUnitTerm($siBaseUnits->get('m')), new SiBaseUnitTerm($siBaseUnits->get('m'), -1)), $physicalQuantities->get('α')))
            ->set(new SiDerivedUnit('steradian', 'sr', new UnitExpression(1., new SiBaseUnitTerm($siBaseUnits->get('m'), 2), new SiBaseUnitTerm($siBaseUnits->get('m'), -2)), $physicalQuantities->get('ω')))
            ->set(new SiDerivedUnit('gram', 'g', new UnitExpression(1.E-3, new SiBaseUnitTerm($siBaseUnits->get('kg'))), $physicalQuantities->get('m')))
            ->set(new SiDerivedUnit('hertz', 'Hz', new UnitExpression(1., new SiBaseUnitTerm($siBaseUnits->get('s'), -1)), $physicalQuantities->get('f')))
            ->set(new SiDerivedUnit('newton', 'N', new UnitExpression(1., new SiBaseUnitTerm($siBaseUnits->get('m')), new SiBaseUnitTerm($siBaseUnits->get('kg')), new SiBaseUnitTerm($siBaseUnits->get('s'), -2)), $physicalQuantities->get('F')))
            ->set(new SiDerivedUnit('pascal', 'Pa', new UnitExpression(1., new SiBaseUnitTerm($siBaseUnits->get('kg')), new SiBaseUnitTerm($siBaseUnits->get('m'), -1), new SiBaseUnitTerm($siBaseUnits->get('s'), -2)), $physicalQuantities->get('p')))
            ->set(new SiDerivedUnit('joule', 'J', new UnitExpression(1., new SiBaseUnitTerm($siBaseUnits->get('kg')), new SiBaseUnitTerm($siBaseUnits->get('m'), 2), new SiBaseUnitTerm($siBaseUnits->get('s'), -2)), $physicalQuantities->get('E')))
            ->set(new SiDerivedUnit('watt', 'W', new UnitExpression(1., new SiBaseUnitTerm($siBaseUnits->get('kg')), new SiBaseUnitTerm($siBaseUnits->get('m'), 2), new SiBaseUnitTerm($siBaseUnits->get('s'), -3)), $physicalQuantities->get('P')))
            ->set(new SiDerivedUnit('coulomb', 'C', new UnitExpression(1., new SiBaseUnitTerm($siBaseUnits->get('s')), new SiBaseUnitTerm($siBaseUnits->get('A'))), $physicalQuantities->get('q')))
            ->set(new SiDerivedUnit('volt', 'V', new UnitExpression(1., new SiBaseUnitTerm($siBaseUnits->get('kg')), new SiBaseUnitTerm($siBaseUnits->get('m'), 2), new SiBaseUnitTerm($siBaseUnits->get('s'), -3), new SiBaseUnitTerm($siBaseUnits->get('A'), -1)), $physicalQuantities->get('U')))
            ->set(new SiDerivedUnit('farad', 'F', new UnitExpression(1., new SiBaseUnitTerm($siBaseUnits->get('kg'), -1), new SiBaseUnitTerm($siBaseUnits->get('m'), -2), new SiBaseUnitTerm($siBaseUnits->get('s'), 4), new SiBaseUnitTerm($siBaseUnits->get('A'), 2)), $physicalQuantities->get('V')))
            ->set(new SiDerivedUnit('ohm', 'Ω', new UnitExpression(1., new SiBaseUnitTerm($siBaseUnits->get('kg')), new SiBaseUnitTerm($siBaseUnits->get('m'), 2), new SiBaseUnitTerm($siBaseUnits->get('s'), -3), new SiBaseUnitTerm($siBaseUnits->get('A'), -2)), $physicalQuantities->get('R')))
            ->set(new SiDerivedUnit('siemens', 'S', new UnitExpression(1., new SiBaseUnitTerm($siBaseUnits->get('kg'), -1), new SiBaseUnitTerm($siBaseUnits->get('m'), -2), new SiBaseUnitTerm($siBaseUnits->get('s'), 3), new SiBaseUnitTerm($siBaseUnits->get('A'), 2)), $physicalQuantities->get('G')))
            ->set(new SiDerivedUnit('weber', 'Wb', new UnitExpression(1., new SiBaseUnitTerm($siBaseUnits->get('kg')), new SiBaseUnitTerm($siBaseUnits->get('m'), 2), new SiBaseUnitTerm($siBaseUnits->get('s'), -2), new SiBaseUnitTerm($siBaseUnits->get('A'), -1)), $physicalQuantities->get('Φ')))
            ->set(new SiDerivedUnit('tesla', 'T', new UnitExpression(1., new SiBaseUnitTerm($siBaseUnits->get('kg')), new SiBaseUnitTerm($siBaseUnits->get('s'), -2), new SiBaseUnitTerm($siBaseUnits->get('A'), -1)), $physicalQuantities->get('B')))
            //->set((new SiDerivedUnit('mole per cubic metre', 'mol/m³', new UnitExpression(1., new SiBaseUnitTerm($siBaseUnits->get('mol')), new SiBaseUnitTerm($siBaseUnits->get('m'), -3)), $physicalQuantities->get('C')))->addSymbols('mol/m^3'))
        ;
    }

    /**
     * @param PhysicalQuantityCollection $physicalQuantities
     * @param UnitCollection $siBaseUnits
     * @param bool $preferUsc
     * @return static
     * @throws CollectionException
     * @throws UnitExpressionException
     */
    public static function createNonSiUnits(PhysicalQuantityCollection $physicalQuantities, UnitCollection $siBaseUnits, bool $preferUsc = false): self
    {
        $impPrefix = $preferUsc ? 'imp ' : '';
        $uscPrefix = $preferUsc ? '' : 'US ';

        return (new static())
            ->set((new NonSiUnit('litre', 'L', new UnitExpression(1.E-3, new SiBaseUnitTerm($siBaseUnits->get('m'), 3)), $physicalQuantities->get('V'), true))->addSymbols('l'))
            ->set(new NonSiUnit('foot', 'ft', new UnitExpression(0.3048, new SiBaseUnitTerm($siBaseUnits->get('m'))), $physicalQuantities->get('l')), true)
            ->set((new NonSiUnit('inch', 'in', new UnitExpression(25.4E-3, new SiBaseUnitTerm($siBaseUnits->get('m'))), $physicalQuantities->get('l')))->addSymbols('″'))
            ->set(new NonSiUnit('yard', 'yd', new UnitExpression(0.9144, new SiBaseUnitTerm($siBaseUnits->get('m'))), $physicalQuantities->get('l')))
            ->set(new NonSiUnit('mile', 'mi', new UnitExpression(1609.344, new SiBaseUnitTerm($siBaseUnits->get('m'))), $physicalQuantities->get('l')))
            ->set(new NonSiUnit('tonne', 't', new UnitExpression(1.E3, new SiBaseUnitTerm($siBaseUnits->get('kg'))), $physicalQuantities->get('m'), true))
            ->set((new NonSiUnit('cubic inch', 'in³', new UnitExpression(16.387064E-6, new SiBaseUnitTerm($siBaseUnits->get('m'), 3)), $physicalQuantities->get('V')))->addSymbols('cu in'))
            ->set((new NonSiUnit('cubic yard', 'yd³', new UnitExpression(0.764554857984, new SiBaseUnitTerm($siBaseUnits->get('m'), 3)), $physicalQuantities->get('V')))->addSymbols('cu yd'))
            ->set((new NonSiUnit('cubic foot', 'ft³', new UnitExpression(28.316846592E-3, new SiBaseUnitTerm($siBaseUnits->get('m'), 3)), $physicalQuantities->get('V')))->addSymbols('cu ft'))
            ->set(new NonSiUnit(sprintf('%sgallon', $impPrefix), sprintf('%sgal', $impPrefix), new UnitExpression(4.54609E-3, new SiBaseUnitTerm($siBaseUnits->get('m'), 3)), $physicalQuantities->get('V')))
            ->set(new NonSiUnit(sprintf('%sgallon', $uscPrefix), sprintf('%sgal', $uscPrefix), new UnitExpression(3.785411784E-3, new SiBaseUnitTerm($siBaseUnits->get('m'), 3)), $physicalQuantities->get('V')))
            ->set(new NonSiUnit(sprintf('%sfluid ounce', $impPrefix), sprintf('%sfl oz', $impPrefix), new UnitExpression(28.41306E-6, new SiBaseUnitTerm($siBaseUnits->get('m'), 3)), $physicalQuantities->get('V')))
            ->set(new NonSiUnit(sprintf('%sfluid ounce', $uscPrefix), sprintf('%sfl oz', $uscPrefix), new UnitExpression(29.57353E-6, new SiBaseUnitTerm($siBaseUnits->get('m'), 3)), $physicalQuantities->get('V')))
            ->set((new NonSiUnit(sprintf('%spint', $impPrefix), sprintf('%spt', $impPrefix), new UnitExpression(568.26125E-6, new SiBaseUnitTerm($siBaseUnits->get('m'), 3)), $physicalQuantities->get('V')))->addSymbols(sprintf('%sp', $impPrefix)))
            ->set((new NonSiUnit(sprintf('%sliquid pint', $uscPrefix), sprintf('%spt', $uscPrefix), new UnitExpression(473.176473E-6, new SiBaseUnitTerm($siBaseUnits->get('m'), 3)), $physicalQuantities->get('V')))->addSymbols('liq. pt', 'US liq. pt'))
            ->set((new NonSiUnit(sprintf('%sdry pint', $uscPrefix), 'dry pt', new UnitExpression(473.176473E-6, new SiBaseUnitTerm($siBaseUnits->get('m'), 3)), $physicalQuantities->get('V')))->addSymbols('US dry pt'))
            ->set(new NonSiUnit('chain', 'ch', new UnitExpression(20.1168, new SiBaseUnitTerm($siBaseUnits->get('m'))), $physicalQuantities->get('l')))
            ;
    }

    /**
     * @param string $className
     * @return $this
     * @throws CollectionException
     */
    protected function filterUnitsByType(string $className): self
    {
        if (!is_subclass_of($className, Unit::class)) {
            throw CollectionException::classIsNotAUnit($className);
        }

        return $this->filterByClosure(function (Unit $unit) use ($className) {
            return $unit instanceof $className;
        });
    }
}
