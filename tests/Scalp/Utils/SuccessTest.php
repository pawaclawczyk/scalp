<?php

declare(strict_types=1);

namespace Scalp\Tests\Utils;

use function Scalp\Utils\Failure;
use Scalp\Utils\Failure;
use Scalp\Utils\Success;
use function Scalp\Utils\Success;
use PHPUnit\Framework\TestCase;
use function Scalp\Utils\TryCatch;
use Scalp\Utils\TryCatch;

class SuccessTest extends TestCase
{
    /** @var Success */
    private $success;

    /** @test */
    public function it_is_created_by_try_catch_when_delayed_call_throws_exception(): void
    {
        $this->assertInstanceOf(Success::class, TryCatch(function (): int { return (int) (5 / 2); }));
    }

    /** @test */
    public function it_can_be_casted_to_string(): void
    {
        $this->assertEquals(
            'Success[Scalp\Tests\Utils\Example](42)',
            (string) Success(new Example(42))
        );
    }

    /** @test */
    public function it_is_not_failure(): void
    {
        $this->assertFalse($this->success->isFailure());
    }

    /** @test */
    public function it_is_success(): void
    {
        $this->assertTrue($this->success->isSuccess());
    }

    /** @test */
    public function get_or_else_will_return_value_from_this(): void
    {
        $this->assertEquals(42, $this->success->getOrElse(13));
    }

    /** @test */
    public function flat_map_will_call_function_with_value_from_this_and_return_result(): void
    {
        $function = function (int $x): TryCatch { return Success($x * $x); };

        $this->assertEquals(Success(1764), $this->success->flatMap($function));
    }

    /** @test */
    public function flat_map_will_will_return_failure_if_called_function_results_with_failure(): void
    {
        $function = function (int $x): TryCatch { return Failure(new \DomainException()); };

        $this->assertEquals(Failure(new \DomainException()), $this->success->flatMap($function));
    }

    /** @test */
    public function flat_map_will_try_catch_function_call(): void
    {
        $function = function (int $x): TryCatch { throw new \DomainException(); };

        $this->assertEquals(Failure(new \DomainException()), $this->success->flatMap($function));
    }

    /** @test */
    public function flat_map_requires_callable_that_returns_try_catch_instance(): void
    {
        $this->expectException(\TypeError::class);

        $f = function (int $x) {
            return $x * $x;
        };

        $this->success->flatMap($f);
    }

    /** @test */
    public function classic_example_with_dividing_by_zero(): void
    {
        $divisor = Success(0);
        $dividend = Success(42);

        $result = $dividend->flatMap(function ($x) use ($divisor): TryCatch {
            return $divisor->flatMap(function ($y) use ($x): TryCatch {
                return Success($x / $y);
            });
        });

        $this->assertInstanceOf(Failure::class, $result);
    }

    /** @test */
    public function map_will_call_function_with_value_from_this_within_try_catch(): void
    {
        $function = function (int $x): int { return $x * $x; };

        $this->assertEquals(
            Success(1764),
            $this->success->map($function)
        );
    }

    /** @test */
    public function map_will_call_function_throwing_exception_within_try_catch_and_return_failure(): void
    {
        $function = function (int $x): int { throw new \DomainException(); };

        $this->assertEquals(
            Failure(new \DomainException()),
            $this->success->map($function)
        );
    }

    /** @test */
    public function map_will_call_function_returning_success_within_try_catch_and_return_nested_success_type(): void
    {
        $function = function (int $x): TryCatch { return Success($x * $x); };

        $this->assertEquals(
            Success(Success(1764)),
            $this->success->map($function)
        );
    }

    /** @test */
    public function map_will_call_function_returning_failure_within_try_catch_and_return_nested_success_failure_type(): void
    {
        $function = function (int $x): TryCatch { return Failure(new \DomainException()); };

        $this->assertEquals(
            Success(Failure(new \DomainException())),
            $this->success->map($function)
        );
    }

    protected function setUp(): void
    {
        $this->success = Success(42);

        parent::setUp();
    }
}
