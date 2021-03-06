<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
use bovigo\assert\AssertionFailure;
use PHPUnit\Framework\TestCase;

use function bovigo\assert\assertFalse;
use function bovigo\assert\assertThat;
use function bovigo\assert\assertTrue;
use function bovigo\assert\expect;
/**
 * Tests for bovigo\assert\predicate\StringEndsWith.
 *
 * @group  predicate
 * @since  1.1.0
 */
class StringEndsWithTest extends TestCase
{
    /**
     * @test
     */
    public function nonStringValuesThrowInvalidArgumentException()
    {
        expect(function() { endsWith('foo')->test(303); })
                ->throws(\InvalidArgumentException::class);
    }

    /**
     * @return  array
     */
    public function trueValues(): array
    {
        return [
          'string which ends with and contains foo' => ['barfoobazfoo'],
          'string which ends with foo'              => ['barbazfoo']
        ];
    }

    /**
     * @param  string  $value
     * @test
     * @dataProvider  trueValues
     */
    public function evaluatesToTrueIfStringStartsWithPrefix($value)
    {
        assertTrue(endsWith('foo')->test($value));
    }

    /**
     * @return  array
     */
    public function falseValues(): array
    {
        return [
          'string which contains foo'    => ['barfoobaz'],
          'string which starts with foo' => ['foobarbaz']
        ];
    }

    /**
     * @param  string  $value
     * @test
     * @dataProvider  falseValues
     */
    public function evaluatesToFalseIfStringDoesNotEndWithSuffix($value)
    {
        assertFalse(endsWith('foo')->test($value));
    }

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformation()
    {
        expect(function() { assertThat('bar', endsWith('foo')); })
                ->throws(AssertionFailure::class)
                ->withMessage("Failed asserting that 'bar' ends with 'foo'.");
    }
}
