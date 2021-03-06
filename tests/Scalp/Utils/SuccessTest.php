<?php

declare(strict_types=1);

namespace Scalp\Tests\Utils;

use Scalp\Exception\UnsupportedOperationException;
use function Scalp\Some;
use Scalp\Tests\Conversion\ExampleWithoutConversionToString;
use Scalp\Tests\RememberCall;
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
    public function or_else_will_return_this(): void
    {
        $this->assertEquals($this->success, $this->success->orElse(Success(13)));
    }

    /** @test */
    public function get_will_return_value_from_this(): void
    {
        $this->assertEquals(42, $this->success->get());
    }

    /** @test */
    public function foreach_does_applies_function(): void
    {
        $function = new RememberCall();

        $result = $this->success->foreach($function);

        $this->assertNull($result);
        $this->assertEquals(42, $function->calledWith());
    }

    /** @test */
    public function foreach_does_not_return_result(): void
    {
        $function = function (int $x): int {
            return $x * $x;
        };

        $result = $this->success->foreach($function);

        $this->assertNull($result);
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

    /** @test */
    public function filter_will_return_this_when_predicate_is_satisfied(): void
    {
        $predicate = function (): bool {
            return true;
        };

        $this->assertEquals($this->success, $this->success->filter($predicate));
    }

    /** @test */
    public function filter_will_return_failure_with_no_such_element_exception_when_predicate_is_not_satisfied(): void
    {
        $predicate = function (): bool {
            return false;
        };

        $result = $this->success->filter($predicate);

        $this->assertInstanceOf(Failure::class, $result);
        $this->assertEquals('Failure[Scalp\Exception\NoSuchElementException]("Predicate does not hold for 42")', (string) $result);
    }

    /** @test */
    public function filter_will_return_failure_with_when_predicate_throws_error(): void
    {
        $predicate = function (): bool {
            throw new \RuntimeException('Error from predicate');
        };

        $result = $this->success->filter($predicate);

        $this->assertInstanceOf(Failure::class, $result);
        $this->assertEquals('Failure[RuntimeException]("Error from predicate")', (string) $result);
    }

    /** @test */
    public function recover_with_will_return_this(): void
    {
        $pf = function (\Throwable $error): TryCatch {
            return Success($error->getMessage());
        };

        $this->assertEquals($this->success, $this->success->recoverWith($pf));
    }

    /** @test */
    public function recover_will_return_this(): void
    {
        $pf = function (\Throwable $error): string {
            return $error->getMessage();
        };

        $this->assertEquals($this->success, $this->success->recover($pf));
    }

    /** @test */
    public function to_option_returns_some_with_value_from_this(): void
    {
        $this->assertEquals(Some(42), Success(42)->toOption());
    }

    /** @test */
    public function flatten_will_reduce_all_success_nesting_levels_to_one(): void
    {
        $this->assertEquals(Success('ok'), Success(Success(Success(Success('ok'))))->flatten());
    }

    /** @test */
    public function flatten_will_return_nested_failure(): void
    {
        $failure = Failure(new \RuntimeException());

        $this->assertEquals($failure, Success(Success(Success($failure)))->flatten());
    }

    /** @test */
    public function failed_will_return_failure_With_unsupported_operation_exception(): void
    {
        $this->assertEquals(Failure(new UnsupportedOperationException('Success::failed')), $this->success->failed());
    }

    /** @test */
    public function transform_will_apply_success_function_to_value_from_this(): void
    {
        $s = function (int $x): TryCatch {
            return Success($x * $x);
        };
        $f = function (): TryCatch {
            throw new \RuntimeException('Failure function should never be called');
        };

        $this->assertEquals(Success(1764), $this->success->transform($s, $f));
    }

    /** @test */
    public function transform_will_return_failure_with_new_error_when_success_function_throws_an_error(): void
    {
        $s = function (): TryCatch {
            throw new \RuntimeException('Error from success function');
        };
        $f = function (): TryCatch {
            throw new \RuntimeException('Failure function should never be called');
        };

        $this->assertEquals(Failure(new \RuntimeException('Error from success function')), $this->success->transform($s, $f));
    }

    /** @test */
    public function transform_requires_success_function_to_return_try_catch(): void
    {
        $this->expectException(\TypeError::class);

        $s = function (int $x): int {
            return $x * $x;
        };
        $f = function (): TryCatch {
            throw new \RuntimeException('Failure function should never be called');
        };

        $this->success->transform($s, $f);
    }

    /** @test */
    public function fold_will_call_second_function_with_value_from_this(): void
    {
        $fa = function (): void {
            throw new \RuntimeException('First function should never be called');
        };

        $fb = function (int $x): int {
            return $x * $x;
        };

        $this->assertEquals(1764, $this->success->fold($fa, $fb));
    }

    /** @test */
    public function fold_will_call_second_function_then_first_if_the_second_throws_an_error(): void
    {
        $fa = function (\Throwable $error): string {
            return $error->getMessage();
        };

        $fb = function (): void {
            throw new \RuntimeException('Error from second function');
        };

        $this->assertEquals('Error from second function', $this->success->fold($fa, $fb));
    }

    /** @test */
    public function it_converts_self_to_string_when_contains_object_that_cannot_be_converted_to_string(): void
    {
        $exampleWithoutConversionToString = new ExampleWithoutConversionToString();
        $objectHashId = spl_object_hash($exampleWithoutConversionToString);

        $this->assertEquals(
            "Success[Scalp\Tests\Conversion\ExampleWithoutConversionToString]($objectHashId)",
            Success($exampleWithoutConversionToString)
        );
    }

    protected function setUp(): void
    {
        $this->success = Success(42);

        parent::setUp();
    }
}
