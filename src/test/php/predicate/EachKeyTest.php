<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
use bovigo\assert\AssertionFailure;

use function bovigo\assert\assert;
use function bovigo\assert\assertFalse;
use function bovigo\assert\assertTrue;
use function bovigo\assert\fail;
/**
 * Helper class for the test.
 */
class IteratorAggregateEachKeyExample implements \IteratorAggregate
{
    private $iterator;

    public function __construct()
    {
        $this->iterator = new \ArrayIterator([303, 313, 'foo']);
    }
    public function getIterator()
    {
        return $this->iterator;
    }
}
/**
 * Tests for bovigo\assert\predicate\EachKey.
 *
 * @group  predicate
 * @since  1.3.0
 */
class EachKeyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException  InvalidArgumentException
     */
    public function testNonIterableValueThrowsInvalidArgumentException()
    {
        eachKey(isNotOfType('int'))->test(303);
    }

    /**
     * @test
     */
    function canBeUsedWithCallable()
    {
        assert([303, 313], eachKey('is_int'));
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfArrayIsEmpty()
    {
        assertTrue(eachKey(isNotOfType('int'))->test([]));
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfTraversableIsEmpty()
    {
        assertTrue(eachKey(isNotOfType('int'))->test(new \ArrayIterator([])));
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfEachKeyInArrayFulfillsPredicate()
    {
        assertTrue(eachKey(isNotOfType('int'))->test(['a' => 303, 'b' => 'foo']));
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfEachKeyInTraversableFulfillsPredicate()
    {
        assertTrue(
                eachKey(isNotOfType('int'))
                        ->test(new \ArrayIterator(['a' => 303, 'b' => 'foo']))
        );
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfSingleKeyInArrayDoesNotFulfillPredicate()
    {
        assertFalse(eachKey(isNotOfType('int'))->test(['a' => 303, 'foo']));
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfSingleValueInTraversableDoesNotFulfillPredicate()
    {
        assertFalse(
                eachKey(isNotOfType('int'))
                        ->test(new \ArrayIterator(['a' =>303, 'foo']))
        );
    }

    /**
     * @test
     */
    public function doesNotMovePointerOfPassedArray()
    {
        $array = [303, 313, 'foo'];
        next($array);
        eachKey(isOfType('int'))->test($array);
        assert(current($array), equals(313));
    }

    /**
     * @test
     */
    public function doesNotMovePointerOfPassedTraversable()
    {
        $traversable = new \ArrayIterator([303, 313, 'foo']);
        $traversable->next();
        eachKey(isOfType('int'))->test($traversable);
        assert($traversable->current(), equals(313));
    }

    /**
     * @test
     */
    public function doesNotMovePointerOfPassedIteratorAggregate()
    {
        $traversable = new IteratorAggregateEachKeyExample();
        $traversable->getIterator()->next();
        eachKey(isOfType('int'))->test($traversable);
        assert($traversable->getIterator()->current(), equals(313));
    }

    /**
     * @test
     */
    public function countReturnsCountOfWrappedPredicate()
    {
        assert(count(eachKey(isGreaterThanOrEqualTo(4))), equals(2));
    }

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformation()
    {
        try {
            assert(['foo'], eachKey(isNotOfType('int')));
        } catch (AssertionFailure $af) {
            assert(
                    $af->getMessage(),
                    equals('Failed asserting that in Array &0 (
    0 => \'foo\'
) each key is not of type "int".')
            );
            return;
        }

        fail('Expected ' . AssertionFailure::class . ', gone none');
    }

    /**
     * @test
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that an array is not empty and each key is not of type "int".
     */
    public function assertionFailureContainsMeaningfulInformationWhenCombined()
    {
        assert([], isNotEmpty()->asWellAs(eachKey(isNotOfType('int'))));
    }
}