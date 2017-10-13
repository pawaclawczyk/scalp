<?php

declare(strict_types=1);

namespace Scalp\Tests;

use function Scalp\curry;
use Scalp\CurriedFunction;
use PHPUnit\Framework\TestCase;
use function Scalp\curryN;
use const Scalp\Tests\Type\sum;

final class CurriedFunctionTest extends TestCase
{
    /** @test */
    public function it_wraps_function_into_curried_function(): void
    {
        $curried = curry(function () { return 42; });

        $this->assertInternalType('callable', $curried);
        $this->assertInstanceOf(CurriedFunction::class, $curried);
    }

    /** @test */
    public function it_invokes_curried_function(): void
    {
        $curried = curry(function (int $x, int $y) { return $x - $y; });

        $this->assertEquals(3, $curried(7, 4));
    }

    /** @test */
    public function it_allows_to_pass_arguments_to_curried_function_in_separate_calls(): void
    {
        $curried = curry(function (int $x, int $y) { return $x - $y; });

        $this->assertEquals(3, $curried(7)(4));
    }

    /** @test */
    public function it_allows_to_pass_any_number_of_arguments_in_any_number_of_calls(): void
    {
        $curried = curry(function (int $x, int $y, int $z): int { return $x + $y + $z; });

        $this->assertEquals(6, $curried(1, 2, 3));
        $this->assertEquals(6, $curried(1)(2, 3));
        $this->assertEquals(6, $curried(1, 2)(3));
        $this->assertEquals(6, $curried(1)(2)(3));
        $this->assertEquals(6, $curried()(1, 2, 3));
    }

    /** @test */
    public function it_requires_optional_arguments_to_be_passed_in_last_call_with_last_required_argument(): void
    {
        $curried = curry(function (int $x, int $y, int $z = 100): int { return $x + $y + $z; });

        $this->assertEquals(103, $curried(1)(2));
        $this->assertEquals(6, $curried(1)(2, 3));

        $this->expectException(\Error::class);

        $curried(1)(2)(3);
    }

    /** @test */
    public function it_requires_optional_arguments_declared_before_required_arguments(): void
    {
        $curried = curry(function (int $x, int $y = 10, int $z): int { return $x + $y + $z; });

        $curried2 = $curried(1)(2);

        $this->assertInstanceOf(CurriedFunction::class, $curried2);
        $this->assertEquals(6, $curried2(3));
    }

    /** @test */
    public function it_requires_variadic_arguments_to_be_passed_in_last_call_with_last_required_argument(): void
    {
        $curried = curry(function (int $x, int $y, int ...$z): int { return $x + $y + array_sum($z); });

        $this->assertEquals(3, $curried(1)(2));
        $this->assertEquals(10, $curried(1)(2, 3, 4));

        $this->expectException(\Error::class);

        $curried(1)(2)(3, 4);
    }

    /** @test */
    public function it_curries_closures(): void
    {
        $curried = curry(function (int $x, int $y): int { return $x + $y; });

        $this->assertInstanceOf(CurriedFunction::class, $curried);
        $this->assertEquals(3, $curried(1)(2));
    }

    /** @test */
    public function it_curries_function(): void
    {
        $curried = curry(sum);

        $this->assertInstanceOf(CurriedFunction::class, $curried);
        $this->assertEquals(3, $curried(1)(2));
    }

    /** @test */
    public function it_curries_object_with_invoke(): void
    {
        $object = new class() {
            public function __invoke(int $x, int $y): int
            {
                return $x + $y;
            }
        };

        $curried = curry($object);

        $this->assertInstanceOf(CurriedFunction::class, $curried);
        $this->assertEquals(3, $curried(1)(2));
    }

    /** @test */
    public function it_curries_class_with_static_method(): void
    {
        $curried = curry([Example::class, 'add']);

        $this->assertInstanceOf(CurriedFunction::class, $curried);
        $this->assertEquals(3, $curried(1)(2));
    }

    /** @test */
    public function it_curries_object_method(): void
    {
        $object = new class() {
            public function add(int $x, int $y): int
            {
                return $x + $y;
            }
        };

        $curried = curry([$object, 'add']);

        $this->assertInstanceOf(CurriedFunction::class, $curried);
        $this->assertEquals(3, $curried(1)(2));
    }

    /** @test */
    public function it_allows_to_enforce_arity_of_curried_function(): void
    {
        $f = function (int ...$args): int {
            return array_sum($args);
        };

        $curried = curry($f);
        $curried2 = curryN($f, 2);

        $this->assertEquals(0, $curried());
        $this->assertEquals(3, $curried(1, 2));

        $this->assertEquals(3, $curried2(1)(2));

        $this->expectException(\Error::class);

        $curried(1)(2);
    }

    /** @test */
    public function it_guards_against_declaring_arity_lower_than_required_number_of_arguments(): void
    {
        $this->expectException(\ArgumentCountError::class);
        $this->expectExceptionMessage('Declared arity of function is 1, but required number of arguments is 2.');

        curryN(function (int $x, int $y): int { return $x + $y; }, 1);
    }
}
