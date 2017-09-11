<?php

declare(strict_types=1);

namespace Scalp\Tests;

use function Scalp\None;
use function Scalp\Option;
use Scalp\Option;
use Scalp\Some;
use function Scalp\Some;
use PHPUnit\Framework\TestCase;

class SomeTest extends TestCase
{
    /** @test */
    public function it_is_created_with_factory_function(): void
    {
        $this->assertInstanceOf(Some::class, Some(42));
    }

    /** @test */
    public function it_is_created_when_option_factory_function_is_called_with_anything_but_null(): void
    {
        $this->assertInstanceOf(Some::class, Option(42));
    }

    /** @test */
    public function it_is_created_with_constructor(): void
    {
        $this->assertInstanceOf(Some::class, new Some(42));
    }

    /** @test */
    public function it_is_not_empty(): void
    {
        $this->assertFalse(Some(42)->isEmpty());
    }

    /** @test */
    public function it_is_defined(): void
    {
        $this->assertTrue(Some(42)->isDefined());
    }

    /** @test */
    public function get_returns_value_from_this(): void
    {
        $this->assertEquals(42, Some(42)->get());
    }

    /** @test */
    public function get_or_else_returns_value_from_this(): void
    {
        $this->assertEquals(42, Some(42)->getOrElse(13));
    }

    /** @test */
    public function or_null_returns_value_from_this(): void
    {
        $this->assertEquals(42, Some(42)->orNull());
    }

    /** @test */
    public function map_applies_function_and_returns_result_wrapped_in_some(): void
    {
        $this->assertEquals(Some(1764), Some(42)->map(function (int $x): int { return $x ** 2; }));
    }

    /** @test */
    public function fold_applies_function_ant_returns_result(): void
    {
        $ifEmpty = function (): int {
            return 13;
        };

        $f = function (int $x): int {
            return $x ** 2;
        };

        $this->assertEquals(1764, Some(42)->fold($ifEmpty, $f));
    }

    /** @test */
    public function flat_map_applies_function_ant_returns_result(): void
    {
        $f = function (int $x): Option {
            return Some($x ** 2);
        };

        $this->assertEquals(Some(1764), Some(42)->flatMap($f));
    }

    /** @test */
    public function flat_map_function_must_return_option(): void
    {
        $this->expectException(\TypeError::class);

        $f = function (int $x): int {
            return $x ** 2;
        };

        Some(42)->flatMap($f);
    }

    /** @test */
    public function flatten_reduces_nested_options(): void
    {
        $this->assertEquals(Some(42), Some(Some(Some(42)))->flatten());
    }

    /** @test */
    public function flatten_reduces_nested_options_until_none(): void
    {
        $this->assertEquals(None(), Some(Some(Some(None())))->flatten());
    }
}
