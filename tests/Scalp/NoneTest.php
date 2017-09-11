<?php

declare(strict_types=1);

namespace Scalp\Tests;

use Scalp\Exception\NoSuchElementException;
use Scalp\None;
use function Scalp\None;
use PHPUnit\Framework\TestCase;
use function Scalp\Option;
use Scalp\Option;
use function Scalp\Some;

class NoneTest extends TestCase
{
    /** @test */
    public function it_is_created_with_factory_function(): void
    {
        $this->assertInstanceOf(None::class, None());
    }

    /** @test */
    public function it_is_created_when_option_factory_function_is_called_with_null(): void
    {
        $this->assertInstanceOf(None::class, Option(null));
    }

    /** @test */
    public function it_is_created_with_constructor(): void
    {
        $this->assertInstanceOf(None::class, new None());
    }

    /** @test */
    public function it_is_empty(): void
    {
        $this->assertTrue(None()->isEmpty());
    }

    /** @test */
    public function it_is_not_defined(): void
    {
        $this->assertFalse(None()->isDefined());
    }

    /** @test */
    public function get_throws_no_such_element_exception(): void
    {
        $this->expectException(NoSuchElementException::class);

        None()->get();
    }

    /** @test */
    public function get_or_else_returns_default_value(): void
    {
        $this->assertEquals(13, None()->getOrElse(13));
    }

    /** @test */
    public function or_null_returns_null(): void
    {
        $this->assertNull(None()->orNull());
    }

    /** @test */
    public function map_returns_this(): void
    {
        $this->assertEquals(None(), None()->map(function (int $x): int { return $x ** 2; }));
    }

    /** @test */
    public function fold_call_if_empty_function(): void
    {
        $ifEmpty = function (): int {
            return 13;
        };

        $f = function (int $x): int {
            return $x ** 2;
        };

        $this->assertEquals(13, None()->fold($ifEmpty, $f));
    }

    /** @test */
    public function flat_map_returns_none(): void
    {
        $f = function (): Option {
            return Some(13);
        };

        $this->assertEquals(None(), None()->flatMap($f));
    }

    /** @test */
    public function flat_map_function_must_return_option(): void
    {
        $this->expectException(\TypeError::class);

        $f = function (): int {
            return 13;
        };

        None()->flatMap($f);
    }

    /** @test */
    public function flatten_returns_none(): void
    {
        $this->assertEquals(None(), None()->flatten());
    }
}
