<?php

namespace Drieschel\UnitsOfMeasurement;

use PHPUnit\Framework\TestCase;

class SiBaseUnitTermTest extends TestCase
{
    public function testIsCompatibleWithSuccess()
    {
        $physicalQuantity1 = new PhysicalQuantity('foo', 'bar', 'yolo');
        $physicalQuantity2 = clone $physicalQuantity1;
        $baseUnit1 = new SiBaseUnit('foo', 'baz', $physicalQuantity1);
        $baseUnit2 = new SiBaseUnit('foo', 'baz', $physicalQuantity2);
        $exponent = mt_rand(0, 5);
        $term1 = new SiBaseUnitTerm($baseUnit1, $exponent);
        $term2 = new SiBaseUnitTerm($baseUnit2, $exponent);
        $this->assertTrue($term1->isCompatibleWith($term2));
        $this->assertTrue($term2->isCompatibleWith($term1));
    }

    public function testIsCompatiblePhysicalQuantityDoesNotMatch()
    {
        $physicalQuantity1 = new PhysicalQuantity('foo', 'bar', 'yolo');
        $physicalQuantity2 = new PhysicalQuantity('foo', 'baz', 'yolo');
        $baseUnit1 = new SiBaseUnit('foo', 'baz', $physicalQuantity1);
        $baseUnit2 = new SiBaseUnit('foo', 'baz', $physicalQuantity2);
        $exponent = mt_rand(0, 5);
        $term1 = new SiBaseUnitTerm($baseUnit1, $exponent);
        $term2 = new SiBaseUnitTerm($baseUnit2, $exponent);
        $this->assertFalse($term1->isCompatibleWith($term2));
        $this->assertFalse($term2->isCompatibleWith($term1));
    }

    public function testIsCompatibleExponentDoesNotMatch()
    {
        $physicalQuantity1 = new PhysicalQuantity('foo', 'bar', 'yolo');
        $physicalQuantity2 = new PhysicalQuantity('foo', 'baz', 'yolo');
        $baseUnit1 = new SiBaseUnit('foo', 'baz', $physicalQuantity1);
        $baseUnit2 = new SiBaseUnit('foo', 'baz', $physicalQuantity2);
        $term1 = new SiBaseUnitTerm($baseUnit1, 0);
        $term2 = new SiBaseUnitTerm($baseUnit2, 1);
        $this->assertFalse($term1->isCompatibleWith($term2));
        $this->assertFalse($term2->isCompatibleWith($term1));
    }

    public function testIsCompatibleBaseUnitDoesNotMatch()
    {
        $physicalQuantity1 = new PhysicalQuantity('foo', 'bar', 'yolo');
        $physicalQuantity2 = new PhysicalQuantity('foo', 'bar', 'yolo');
        $baseUnit1 = new SiBaseUnit('foo', 'baz', $physicalQuantity1);
        $baseUnit2 = new SiBaseUnit('foo', 'bar', $physicalQuantity2);
        $exponent = mt_rand(0, 5);
        $term1 = new SiBaseUnitTerm($baseUnit1, $exponent);
        $term2 = new SiBaseUnitTerm($baseUnit2, $exponent);
        $this->assertFalse($term1->isCompatibleWith($term2));
        $this->assertFalse($term2->isCompatibleWith($term1));
    }
}
