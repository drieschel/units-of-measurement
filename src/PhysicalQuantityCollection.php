<?php
namespace Drieschel\UnitsOfMeasurement;

/**
 * Class PhysicalQuantityCollection
 * @package Drieschel\UnitsOfMeasurement
 *
 * @method PhysicalQuantity get(string $symbol)
 */
class PhysicalQuantityCollection extends ComponentCollection
{
    /**
     * @param bool $createUnits
     * @param bool $preferUsc
     * @return PhysicalQuantityCollection
     * @throws CollectionException
     * @throws UnitExpressionException
     */
    public static function create(bool $createUnits = false, bool $preferUsc = false): self
    {
        $quantities = (new static())
            ->set(new PhysicalQuantity('length', 'l', 'L', true))
            ->set(new PhysicalQuantity('mass', 'm', 'M', true))
            ->set(new PhysicalQuantity('time', 't', 'T', true))
            ->set(new PhysicalQuantity('electric current', 'I', 'I', true))
            ->set(new PhysicalQuantity('temperature', 'T', 'Θ', true))
            ->set(new PhysicalQuantity('amount of substance', 'n', 'N', true))
            ->set(new PhysicalQuantity('luminous intensity', 'L', 'J', true))
            ->set(new PhysicalQuantity('volume', 'V', 'L³'))
            ->set(new PhysicalQuantity('frequency', 'f', 'T⁻¹'))
            //->set(new PhysicalQuantity('molar concentration', 'C', 'L⁻³N'))
            ->set((new PhysicalQuantity('plane angle', 'α', '1'))->addSymbols('β', 'γ', 'θ'))
            ->set((new PhysicalQuantity('solid angle', 'ω', '1'))->addSymbols('Ω'))
            ->set(new PhysicalQuantity('force', 'F', 'MLT⁻²'))
            ->set(new PhysicalQuantity('energy', 'E', 'ML²T⁻²'))
            ->set(new PhysicalQuantity('pressure', 'p', 'ML⁻¹T⁻²'))
            ->set(new PhysicalQuantity('power', 'P', 'L²MT⁻³'))
            ->set(new PhysicalQuantity('electric charge', 'q', 'TI'))
            ->set(new PhysicalQuantity('voltage', 'U', 'ML²T⁻³I⁻¹'))
            ->set(new PhysicalQuantity('capacitance', 'C', 'M⁻¹L⁻²T⁴I²'))
            ->set(new PhysicalQuantity('electrical resistance', 'R', 'ML²T⁻³I⁻²'))
            ->set(new PhysicalQuantity('electrical conductance', 'G', 'M⁻¹L⁻²T³I²'))
            ->set(new PhysicalQuantity('magnetic flux', 'Φ', 'ML²T⁻²I⁻¹'))
            ->set(new PhysicalQuantity('magnetic flux density', 'B', 'MT⁻²I⁻¹'))
        ;

        if ($createUnits) {
            UnitCollection::createAllUnits($quantities, $preferUsc);
        }

        return $quantities;
    }
}
