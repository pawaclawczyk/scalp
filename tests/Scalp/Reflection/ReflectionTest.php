<?php

declare(strict_types=1);

namespace Scalp\Tests\Reflection;

use PHPUnit\Framework\TestCase;
use function Scalp\Reflection\reflectionFunction;
use Scalp\Tests\Example;
use const Scalp\Tests\Type\sum;

final class ReflectionTest extends TestCase
{
    /** @test */
    public function reflection_function_returns_reflection_of_closure(): void
    {
        $closure = function (int $x, int $y): int {
            return $x + $y;
        };

        $r = reflectionFunction($closure);

        $this->assertCount(2, $r->getParameters());
        $this->assertEquals('int', (string) $r->getReturnType());
    }

    /** @test */
    public function reflection_function_returns_reflection_of_function(): void
    {
        $r = reflectionFunction(sum);

        $this->assertCount(2, $r->getParameters());
        $this->assertEquals('int', (string) $r->getReturnType());
    }

    /** @test */
    public function reflection_function_returns_reflection_of_object_with_invoke(): void
    {
        $object = new class() {
            public function __invoke(int $x, int $y): int
            {
                return $x + $y;
            }
        };

        $r = reflectionFunction($object);

        $this->assertCount(2, $r->getParameters());
        $this->assertEquals('int', (string) $r->getReturnType());
    }

    /** @test */
    public function reflection_function_returns_reflection_of_class_static_method(): void
    {
        $r = reflectionFunction([Example::class, 'add']);

        $this->assertCount(2, $r->getParameters());
        $this->assertEquals('int', (string) $r->getReturnType());
    }

    /** @test */
    public function reflection_function_returns_reflection_of_object_method(): void
    {
        $object = new class() {
            public function add(int $x, int $y): int
            {
                return $x + $y;
            }
        };

        $r = reflectionFunction([$object, 'add']);

        $this->assertCount(2, $r->getParameters());
        $this->assertEquals('int', (string) $r->getReturnType());
    }
}
