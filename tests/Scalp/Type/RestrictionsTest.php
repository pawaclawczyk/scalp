<?php

declare(strict_types=1);

namespace Scalp\Tests\Type;

use PHPUnit\Framework\TestCase;
use function Scalp\Type\restrictCallableReturnType;

final class RestrictionsTest extends TestCase
{
    /** @test */
    public function it_restricts_return_type_of_closure(): void
    {
        $f = function (): int { return 42; };

        $this->assertInstanceOf(\Closure::class, $f);

        restrictCallableReturnType($f, 'int');

        $this->assertTrue(true);
    }

    /** @test */
    public function it_throws_exception_when_return_type_does_not_match_for_closure(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('Return value of callable must be defined and must have type string.');

        $f = function (): int { return 13; };

        restrictCallableReturnType($f, 'string');
    }

    /** @test */
    public function it_throws_exception_when_return_type_is_not_defined_for_closure(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('Return value of callable must be defined and must have type int.');

        $f = function () { return 13; };

        restrictCallableReturnType($f, 'int');
    }

    /** @test */
    public function it_checks_instances_of_object_implementing_invoke_method(): void
    {
        $f = new class() {
            public function __invoke(): int
            {
                return 42;
            }
        };

        restrictCallableReturnType($f, 'int');

        $this->assertTrue(true);
    }

    /** @test */
    public function it_throws_exception_when_return_type_does_not_match_for_object(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('Return value of callable must be defined and must have type string.');

        $f = new class() {
            public function __invoke(): int
            {
                return 13;
            }
        };

        restrictCallableReturnType($f, 'string');

        $this->assertTrue(true);
    }

    /** @test */
    public function it_throws_exception_when_return_type_is_not_defined_for_object(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('Return value of callable must be defined and must have type int.');

        $f = new class() {
            public function __invoke()
            {
                return 13;
            }
        };

        restrictCallableReturnType($f, 'int');
    }

    /** @test */
    public function it_checks_instances_of_functions(): void
    {
        restrictCallableReturnType(functionWithReturnType, 'int');

        $this->assertTrue(true);
    }

    /** @test */
    public function it_throws_exception_when_return_type_does_not_match_for_functions(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('Return value of callable must be defined and must have type string.');

        restrictCallableReturnType(functionWithReturnType, 'string');

        $this->assertTrue(true);
    }

    /** @test */
    public function it_throws_exception_when_return_type_is_not_defined_for_function(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('Return value of callable must be defined and must have type int.');

        restrictCallableReturnType(functionWithoutReturnType, 'int');
    }
}
