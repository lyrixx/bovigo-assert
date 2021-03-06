<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
use PHPUnit\Framework\TestCase;

use function bovigo\assert\assertThat;
use function bovigo\assert\assertTrue;
/**
 * Test for bovigo\assert\predicate\NegatePredicate.
 *
 * @group  predicate
 */
class NegatePredicateTest extends TestCase
{
    /**
     *
     * @type  \bovigo\assert\predicate\NegatePredicate
     */
    private $negatePredicate;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->negatePredicate = not(
                function($value) { return 'foo' === $value; }
        );
    }
    /**
     * @test
     */
    public function negatesWrappedPredicate()
    {
        assertTrue($this->negatePredicate->test('bar'));
    }

    public function predicates(): array
    {
        return [
            [not(function($value) { return 'foo' === $value; }), 'does not satisfy a lambda function'],
            [not(equals(5)->or(isLessThan(5))), 'not (is equal to 5 or is less than 5)'],
            [not(equals(5)->and(isLessThan(5))), 'not (is equal to 5 and is less than 5)'],
            [not(not(equals(5))), 'not (is not equal to 5)']
        ];
    }

    /**
     * @test
     * @dataProvider  predicates
     */
    public function hasStringRepresentation(NegatePredicate $negatePredicate, $expected)
    {
        assertThat((string) $negatePredicate, equals($expected));
    }

    /**
     * @test
     */
    public function countEqualsCountOfNegatedPredicate()
    {
        assertThat(
                count(not(new AndPredicate(function() {}, function() {}))),
                equals(2)
        );
    }
}
