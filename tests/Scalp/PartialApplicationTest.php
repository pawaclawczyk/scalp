<?php

declare(strict_types=1);

namespace Scalp\Tests;

use PHPUnit\Framework\TestCase;
use const Scalp\__;
use const Scalp\concat;
use function Scalp\papply;
use const Scalp\Tests\Type\sum;

final class PartialApplicationTest extends TestCase
{
    /** @test */
    public function it_partially_applies_closure(): void
    {
        $f = function (int $x, int $y): int {
            return $x + $y;
        };

        $partiallyAppliedF = papply($f, __, 3);

        $this->assertInternalType('callable', $partiallyAppliedF);

        $this->assertEquals(3, $partiallyAppliedF(0));
        $this->assertEquals(0, $partiallyAppliedF(-3));
        $this->assertEquals(6, $partiallyAppliedF(3));
    }

    /** @test */
    public function it_keep_order_of_arguments(): void
    {
        $f = function (int $x, int $y): int {
            return $x - $y;
        };

        $partiallyAppliedF = papply($f, __, __);

        $this->assertInternalType('callable', $partiallyAppliedF);

        $this->assertEquals(6, $partiallyAppliedF(3, -3));
        $this->assertEquals(-6, $partiallyAppliedF(-3, 3));
    }

    /** @test */
    public function it_partially_applies_function(): void
    {
        $partiallyAppliedF = papply(sum, __, 3);

        $this->assertInternalType('callable', $partiallyAppliedF);

        $this->assertEquals(3, $partiallyAppliedF(0));
        $this->assertEquals(0, $partiallyAppliedF(-3));
        $this->assertEquals(6, $partiallyAppliedF(3));
    }

    /** @test */
    public function it_partially_applies_object_with_invoke(): void
    {
        $object = new class() {
            public function __invoke(int $x, int $y): int
            {
                return $x + $y;
            }
        };

        $partiallyAppliedF = papply($object, __, 3);

        $this->assertInternalType('callable', $partiallyAppliedF);

        $this->assertEquals(3, $partiallyAppliedF(0));
        $this->assertEquals(0, $partiallyAppliedF(-3));
        $this->assertEquals(6, $partiallyAppliedF(3));
    }

    /** @test */
    public function it_partially_applies_class_static_method(): void
    {
        $partiallyAppliedF = papply([Example::class, 'add'], __, 3);

        $this->assertInternalType('callable', $partiallyAppliedF);

        $this->assertEquals(3, $partiallyAppliedF(0));
        $this->assertEquals(0, $partiallyAppliedF(-3));
        $this->assertEquals(6, $partiallyAppliedF(3));
    }

    /** @test */
    public function it_partially_applies_object_method(): void
    {
        $object = new class() {
            public function add(int $x, int $y): int
            {
                return $x + $y;
            }
        };

        $partiallyAppliedF = papply([$object, 'add'], __, 3);

        $this->assertInternalType('callable', $partiallyAppliedF);

        $this->assertEquals(3, $partiallyAppliedF(0));
        $this->assertEquals(0, $partiallyAppliedF(-3));
        $this->assertEquals(6, $partiallyAppliedF(3));
    }

    /** @test */
    public function it_throws_bad_function_call_exception_when_placeholder_is_not_filled(): void
    {
        $f = function (int $x, int $y): int {
            return $x + $y;
        };

        $partiallyAppliedF = papply($f, __, __);

        $this->expectException(\BadFunctionCallException::class);
        $this->expectExceptionMessage('Partially applied function has 1 missing argument at position: 2.');

        $partiallyAppliedF(3);
    }

    /** @test */
    public function it_throws_bad_function_call_exception_when_not_all_required_arguments_are_placeholders_or_values(): void
    {
        $f = function (int $x, int $y): int {
            return $x + $y;
        };

        $this->expectException(\BadFunctionCallException::class);
        $this->expectExceptionMessage('Number of passed arguments is less than required arguments. Use `__` const to add placeholder or value to apply.');

        papply($f, __);
    }

    /** @test */
    public function it_does_not_require_variadic_arguments(): void
    {
        $f = function (int ...$v): int {
            return array_sum($v);
        };

        $partiallyAppliedF = papply($f);

        $this->assertEquals(0, $f());
        $this->assertEquals(0, $partiallyAppliedF());
    }

    /** @test */
    public function it_enforces_to_pass_variadic_arguments_when_placeholders_are_defined(): void
    {
        $f = function (int ...$v): int {
            return array_sum($v);
        };

        $partiallyAppliedF = papply($f, __, __);

        $this->expectException(\BadFunctionCallException::class);
        $this->expectExceptionMessage('Partially applied function has 2 missing arguments at position: 1, 2.');

        $partiallyAppliedF();
    }

    /** @test */
    public function it_allows_values_and_placeholders_to_be_on_random_positions(): void
    {
        $f = papply(concat, 'It', __, ' values and', __, ' to be on', ' random', __);
        $g = papply(concat, __, ' allows', __, ' placeholders', __, __, ' positions.');

        $this->assertEquals('It allows values and placeholders to be on random positions.', $f(' allows', ' placeholders', ' positions.'));
        $this->assertEquals('It allows values and placeholders to be on random positions.', $g('It', ' values and', ' to be on', ' random'));
    }
}
