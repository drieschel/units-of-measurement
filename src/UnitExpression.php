<?php

namespace Drieschel\UnitsOfMeasurement;

class UnitExpression
{
    /**
     * @var float
     */
    protected $factor = 1.;

    /**
     * @var SiBaseUnitTerm[]
     */
    protected $terms = [];

    /**
     * UnitExpression constructor.
     * @param float $factor
     * @param SiBaseUnitTerm $firstTerm
     * @param SiBaseUnitTerm ...$moreTerms
     * @throws UnitExpressionException
     */
    public function __construct(float $factor = 1., SiBaseUnitTerm $firstTerm, SiBaseUnitTerm ...$moreTerms)
    {
        if ($factor === 0.) {
            throw UnitExpressionException::invalidFactor($factor);
        }

        $this->factor = $factor;
        $this->terms = array_merge([$firstTerm], $moreTerms);
    }

    /**
     * @return float
     */
    public function getFactor(): float
    {
        return $this->factor;
    }

    /**
     * @return SiBaseUnitTerm[]
     */
    public function getTerms(): array
    {
        return $this->terms;
    }

    /**
     * @param UnitExpression $unitExpression
     * @return boolean
     */
    public function isCompatibleWith(UnitExpression $unitExpression): bool
    {
        $termsCnt = count($this->terms);
        $otherTermsCnt = count($unitExpression->terms);

        if ($termsCnt !== $otherTermsCnt) {
            return false;
        }

        $compatibleTerms = [];
        foreach ($this->terms as $i => $term) {
            foreach ($unitExpression->terms as $j => $otherTerm) {
                if (!isset($compatibleTerms[$j]) && $term->isCompatibleWith($otherTerm)) {
                    $compatibleTerms[$j] = $otherTerm;
                    break;
                }
            }
        }

        return count($compatibleTerms) === $termsCnt;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $factor = '';
        if ($this->factor !== 1.) {
            $factor = sprintf('%d⋅', $this->factor);
        }

        $parts = array_map(function (SiBaseUnitTerm $term) {
            return $term->__toString();
        }, $this->terms);

        return sprintf('%s%s', $factor, implode('⋅', $parts));
    }
}
