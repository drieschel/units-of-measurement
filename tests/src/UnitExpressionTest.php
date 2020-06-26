<?php

namespace Drieschel\UnitsOfMeasurement;

use PHPUnit\Framework\TestCase;

class UnitExpressionTest extends TestCase
{
    public function testConstructorInvalidFactor()
    {
        $this->expectException(UnitExpressionException::class);
        $this->expectExceptionCode(UnitExpressionException::INVALID_FACTOR);
        new UnitExpression(0, new SiBaseUnitTerm(new SiBaseUnit('wat', 'dat', new PhysicalQuantity('ba', 'ru', 'su'))));
    }

    public function testIsCompatibleWithIsCompatible()
    {
        $termsAmount = mt_rand(1, 10);
        $terms1 = $this->createTerms($termsAmount, true);
        $terms2 = $this->createTerms($termsAmount, true);

        $expressionOne = new UnitExpression(1, ...$terms1);
        $expressionTwo = new UnitExpression(2, ...$terms2);

        $this->assertTrue($expressionOne->isCompatibleWith($expressionTwo));
        $this->assertTrue($expressionTwo->isCompatibleWith($expressionOne));
    }

    public function testIsCompatibleWithInCompatibleTermButSameTermsAmount()
    {
        $termsAmount = mt_rand(1, 10);
        $terms = $this->createTerms($termsAmount, false);

        $expressionOne = new UnitExpression(1, ...$terms);
        $expressionTwo = new UnitExpression(2, ...$terms);

        $this->assertFalse($expressionOne->isCompatibleWith($expressionTwo));
        $this->assertFalse($expressionTwo->isCompatibleWith($expressionOne));
    }

    public function testIsCompatibleDifferentTermsAmount()
    {
        $termsAmount1 = mt_rand(2, 10);
        $termsAmount2 = mt_rand(1, $termsAmount1 - 1);

        $terms1 = $this->createTerms($termsAmount1, true);
        $terms2 = $this->createTerms($termsAmount2, true);

        $expressionOne = new UnitExpression(1, ...$terms1);
        $expressionTwo = new UnitExpression(2, ...$terms2);

        $this->assertFalse($expressionOne->isCompatibleWith($expressionTwo));
        $this->assertFalse($expressionTwo->isCompatibleWith($expressionOne));
    }

    protected function createTerms(int $amount, bool $compatible = true): array
    {
        $terms = [];
        $incompatibleRound = mt_rand(0, $amount - 1);
        for ($i = 0; $i < $amount; $i++) {
            $returnValue = $compatible || $incompatibleRound !== $i;
            $term = $this->createMock(SiBaseUnitTerm::class);
            $term
                //->expects($this->any())
                ->method('isCompatibleWith')
                ->willReturn($returnValue);

            $terms[] = $term;
        }

        return $terms;
    }
}
