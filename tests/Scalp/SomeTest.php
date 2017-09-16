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
    public function fold_applies_function_and_returns_result(): void
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
    public function flat_map_applies_function_and_returns_result(): void
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

    /** @test */
    public function filter_returns_this_when_predicate_is_fulfilled(): void
    {
        $p = function (): bool {
            return true;
        };

        $this->assertEquals(Some(42), Some(42)->filter($p));
    }

    /** @test */
    public function filter_returns_none_when_predicate_is_not_fulfilled(): void
    {
        $p = function (): bool {
            return false;
        };

        $this->assertEquals(None(), Some(42)->filter($p));
    }

    /** @test */
    public function filter_not_returns_this_when_predicate_is_not_fulfilled(): void
    {
        $p = function (): bool {
            return false;
        };

        $this->assertEquals(Some(42), Some(42)->filterNot($p));
    }

    /** @test */
    public function filter_not_returns_none_when_predicate_is_fulfilled(): void
    {
        $p = function (): bool {
            return true;
        };

        $this->assertEquals(None(), Some(42)->filterNot($p));
    }

    /** @test */
    public function contains_returns_true_when_contains_element(): void
    {
        $this->assertTrue(Some(42)->contains(42));
    }

    /** @test */
    public function contains_returns_false_when_does_not_contain_element(): void
    {
        $this->assertFalse(Some(42)->contains(13));
    }

    /** @test */
    public function exists_returns_true_when_predicate_is_fulfilled(): void
    {
        $p = function (): bool {
            return true;
        };

        $this->assertTrue(Some(42)->exists($p));
    }

    /** @test */
    public function exists_returns_false_when_predicate_is_not_fulfilled(): void
    {
        $p = function (): bool {
            return false;
        };

        $this->assertFalse(Some(42)->exists($p));
    }

    /** @test */
    public function forall_returns_true_when_predicate_is_satisfied(): void
    {
        $p = function (): bool {
            return true;
        };

        $this->assertTrue(Some(42)->forall($p));
    }

    /** @test */
    public function forall_returns_false_when_predicate_is_not_satisfied(): void
    {
        $p = function (): bool {
            return false;
        };

        $this->assertFalse(Some(42)->forall($p));
    }

    /** @test */
    public function foreach_applies_function_to_value_from_this(): void
    {
        $remember = new RememberCall();

        Some(42)->foreach($remember);

        $this->assertEquals(42, $remember->calledWith());
    }

    /** @test */
    public function foreach_does_not_return_value(): void
    {
        $f = function (int $x): int {
            return $x;
        };

        $this->assertNull(Some(42)->foreach($f));
    }

    /** @test */
    public function or_else_returns_this(): void
    {
        $this->assertEquals(Some(42), Some(42)->orElse(Some(13)));
    }

    /** @test */
    public function iterator_returns_single_value_iterator(): void
    {
        $it = Some(42)->iterator();

        $this->assertInstanceOf(\Iterator::class, $it);

        $counter = 0;

        foreach ($it as $v) {
            $counter = $counter + 1;
            $this->assertEquals(42, $v);
        }

        $this->assertEquals(1, $counter);
    }

    /** @test */
    public function it_converts_to_string(): void
    {
        $this->assertEquals('Some[integer](42)', Some(42)->toString());
        $this->assertEquals('Some[integer](42)', (string) Some(42));
    }
}
