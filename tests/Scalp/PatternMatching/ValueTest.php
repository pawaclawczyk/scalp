<?php

declare(strict_types=1);

namespace Scalp\Tests\PatternMatching;

use PHPUnit\Framework\TestCase;
use Scalp\Collection\Tuple;
use Scalp\None;
use function Scalp\None;
use function Scalp\PatternMatching\Value;
use Scalp\Some;
use function Scalp\Some;

final class ValueTest extends TestCase
{
    /** @test */
    public function it_matches_value_of_primitive_types(): void
    {
        $this->assertInstanceOf(Some::class, Value(42)->match(42));
        $this->assertInstanceOf(Some::class, Value('42')->match('42'));
        $this->assertInstanceOf(Some::class, Value(true)->match(true));
    }

    /** @test */
    public function it_matches_values_of_primitive_types_in_a_strict_way(): void
    {
        $this->assertInstanceOf(None::class, Value(42)->match('42'));
        $this->assertInstanceOf(None::class, Value(true)->match(false));
        $this->assertInstanceOf(None::class, Value(0)->match(null));
    }

    /** @test */
    public function it_matches_values_of_complex_types(): void
    {
        $this->assertInstanceOf(
            Some::class,
            Value(new Tuple(Some(42), Some(None())))
                ->match(new Tuple(Some(42), Some(None())))
        );
    }

    /** @test */
    public function it_matches_values_of_complex_types_in_a_soft_way(): void
    {
        $this->assertInstanceOf(
            Some::class,
            Value(new Tuple(Some(42), Some(None())))
                ->match(new Tuple(Some('42'), Some(None())))
        );
    }
}
