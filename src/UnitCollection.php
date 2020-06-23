<?php

namespace Drieschel\UnitsOfMeasurement;

/**
 * Class UnitCollection
 * @package Drieschel\UnitsOfMeasure
 *
 * @method NonSiUnit|SiBaseUnit|SiDerivedUnit get(string $symbol)
 */
class UnitCollection extends ComponentCollection
{
    /**
     * @return UnitCollection
     */
    public function getNonSiUnits(): self
    {
        return $this->filterUnitsByType(NonSiUnit::class);
    }

    /**
     * @return UnitCollection
     */
    public function getSiBaseUnits(): self
    {
        return $this->filterUnitsByType(SiBaseUnit::class);
    }

    /**
     * @return UnitCollection
     */
    public function getSiDerivedUnits(): self
    {
        return $this->filterUnitsByType(SiDerivedUnit::class);
    }

    /**
     * @return UnitCollection
     */
    public function getSiPrefixCompatibleUnits(): self
    {
        return $this->filterByClosure(function (Unit $unit) {
            return $unit->isSiPrefixCompatible();
        });
    }

    /**
     * @param PhysicalQuantityCollection|null $physicalQuantities
     * @return UnitCollection
     * @throws ComponentCollectionException
     */
    public static function create(PhysicalQuantityCollection $physicalQuantities = null): self
    {
        if (is_null($physicalQuantities)) {
            $physicalQuantities = PhysicalQuantityCollection::create();
        }

        $self = (new static())
            //->merge($baseUnits)
            ->set(new SiBaseUnit('meter', 'm', $physicalQuantities->get('l')))
            ->set(new SiBaseUnit('kilogram', 'kg', $physicalQuantities->get('m')))
            ->set(new SiBaseUnit('second', 's', $physicalQuantities->get('t')))
            ->set(new SiBaseUnit('ampere', 'A', $physicalQuantities->get('I')))
            ->set(new SiBaseUnit('kelvin', 'K', $physicalQuantities->get('T')))
            ->set(new SiBaseUnit('mole', 'mol', $physicalQuantities->get('n')))
            ->set(new SiBaseUnit('candela', 'cd', $physicalQuantities->get('L')));

        $self
            ->set(new NonSiUnit('inch', 'in', new UnitExpression(25.4E-3, new SiBaseUnitTerm($self->get('m'))), $physicalQuantities->get('l')))
            ->set(new NonSiUnit('foot', 'ft', new UnitExpression(0.3048, new SiBaseUnitTerm($self->get('m'))), $physicalQuantities->get('l')))
            ->set(new NonSiUnit('yard', 'yd', new UnitExpression(0.9144, new SiBaseUnitTerm($self->get('m'))), $physicalQuantities->get('l')))
            ->set(new NonSiUnit('mile', 'mi', new UnitExpression(1609.344, new SiBaseUnitTerm($self->get('m'))), $physicalQuantities->get('l')))
            ->set(new NonSiUnit('chain', 'ch', new UnitExpression(20.1168, new SiBaseUnitTerm($self->get('m'))), $physicalQuantities->get('l')))
            ->set((new NonSiUnit('litre', 'L', new UnitExpression(1E-3, new SiBaseUnitTerm($self->get('m'), 3)), $physicalQuantities->get('V'), true))->addSymbols('l'))
            ->set((new NonSiUnit('cubic inch', 'in³', new UnitExpression(16.387064E-6, new SiBaseUnitTerm($self->get('m'), 3)), $physicalQuantities->get('V')))->addSymbols('cu in'))
            ->set((new NonSiUnit('cubic foot', 'ft³', new UnitExpression(28.316846592E-3, new SiBaseUnitTerm($self->get('m'), 3)), $physicalQuantities->get('V')))->addSymbols('cu ft'))
            ->set((new NonSiUnit('cubic yard', 'yd³', new UnitExpression(0.764554857984, new SiBaseUnitTerm($self->get('m'), 3)), $physicalQuantities->get('V')))->addSymbols('cu yd'))
            //->set((new NonSiUnit('millilitre', 'mL', new UnitExpression(1E-3, new SiBaseUnitTerm($self->get('m'), 3)), $physicalQuantities->get('V'), true))->addSymbols('l'))
            ->set(new NonSiUnit('gallone', 'gal', new UnitExpression(3.785411784E-3, new SiBaseUnitTerm($self->get('m'), 3)), $physicalQuantities->get('V')))
            ->set(new NonSiUnit('tonne', 't', new UnitExpression(1E3, new SiBaseUnitTerm($self->get('kg'))), $physicalQuantities->get('m'), true));

        $self
            ->set(new SiDerivedUnit('gram', 'g', new UnitExpression(1E-3, new SiBaseUnitTerm($self->get('kg'))), $physicalQuantities->get('m'), true))
            ->set(new SiDerivedUnit('centimetre', 'cm', new UnitExpression(1E-2, new SiBaseUnitTerm($self->get('m'))), $physicalQuantities->get('l'), true))
            ->set(new SiDerivedUnit('millimetre', 'mm', new UnitExpression(1E-3, new SiBaseUnitTerm($self->get('m'))), $physicalQuantities->get('l'), true))
            //->set(new SiDerivedUnit('radian', 'rad', new UnitExpression(1., new SiBaseUnitTerm($self->get('m')), new SiBaseUnitTerm($self->get('m'), -1))), $physicalQuantities->get(''))
            //->set(new SiDerivedUnit('steradian', 'sr', new UnitExpression(1., new SiBaseUnitTerm($self->get('m'), 2), new SiBaseUnitTerm($self->get('m'), -2))))
            ->set(new SiDerivedUnit('hertz', 'Hz', new UnitExpression(1., new SiBaseUnitTerm($self->get('s'), -1)), $physicalQuantities->get('f')))
            ->set(new SiDerivedUnit('newton', 'N', new UnitExpression(1., new SiBaseUnitTerm($self->get('m')), new SiBaseUnitTerm($self->get('kg')), new SiBaseUnitTerm($self->get('s'), -2)), $physicalQuantities->get('n')))
            ->set((new SiDerivedUnit('mole per cubic metre', 'mol/m³', new UnitExpression(1., new SiBaseUnitTerm($self->get('mol')), new SiBaseUnitTerm($self->get('m'), -3)), $physicalQuantities->get('C')))->addSymbols('mol/m^3'));

        return $self;
    }

    /**
     * @param string $className
     * @return UnitCollection
     */
    protected function filterUnitsByType(string $className): self
    {
        if (!is_subclass_of($className, Unit::class)) {
            //Not a unit class
        }

        return $this->filterByClosure(function (Unit $unit) use ($className) {
            return $unit instanceof $className;
        });
    }
}
