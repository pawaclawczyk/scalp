<?php

declare(strict_types=1);

namespace Scalp\Tests;

use PHPUnit\Framework\TestCase;
use const Scalp\__;
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
}
