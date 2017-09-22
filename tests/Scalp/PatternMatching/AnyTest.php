<?php

declare(strict_types=1);

namespace Scalp\Tests\PatternMatching;

use function Scalp\None;
use function Scalp\PatternMatching\Any;
use PHPUnit\Framework\TestCase;
use function Scalp\Some;

final class AnyTest extends TestCase
{
    /** @test */
    public function it_matches_any_value(): void
    {
        $this->assertEquals(Some([]), Any()->match(42));
        $this->assertEquals(Some([]), Any()->match('Hello World!'));
        $this->assertEquals(Some([]), Any()->match(null));
        $this->assertEquals(Some([]), Any()->match(None()));
    }

    /** @test */
    public function it_can_bind_value(): void
    {
        $this->assertEquals(
            Some([42]),
            Any()->bind()->match(42)
        );

        $this->assertEquals(
            Some(['Hello World!']),
            Any()->bind()->match('Hello World!')
        );

        $this->assertEquals(
            Some([null]),
            Any()->bind()->match(null)
        );

        $this->assertEquals(
            Some([None()]),
            Any()->bind()->match(None())
        );
    }
}
