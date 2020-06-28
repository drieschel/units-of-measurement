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
            //->set(new PhysicalQuantity('angle', 'α', ''))
            ->set(new PhysicalQuantity('volume', 'V', 'L³'))
            ->set(new PhysicalQuantity('frequency', 'f', 'T⁻¹'))
            ->set(new PhysicalQuantity('molar concentration', 'C', 'L⁻³ N'))
        ;

        if ($createUnits) {
            UnitCollection::createAllUnits($quantities, $preferUsc);
        }

        return $quantities;
    }
}
