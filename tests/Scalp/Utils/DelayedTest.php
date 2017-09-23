<?php

declare(strict_types=1);

namespace Scalp\Tests\Utils;

use Scalp\Utils\Delayed;
use function Scalp\Utils\delay;
use PHPUnit\Framework\TestCase;

class DelayedTest extends TestCase
{
    /** @test */
    public function it_encapsulates_callable_with_argument_and_call_it_on_invoke(): void
    {
        $function = function (int $x) {
            return $x * $x;
        };

        $delayed = new Delayed($function, 2);

        $this->assertEquals(4, $delayed());
    }

    /** @test */
    public function it_applies_to_any_number_of_arguments(): void
    {
        $function = function (int $x, int $y, int $z) {
            return $x + $y * $z;
        };

        $delayed = new Delayed($function, 3, 4, 5);

        $this->assertEquals(23, $delayed());
    }

    /** @test */
    public function it_applies_zero_arguments(): void
    {
        $function = function () {
            return 'Hello';
        };

        $delayed = new Delayed($function);

        $this->assertEquals('Hello', $delayed());
    }

    /** @test */
    public function it_has_helper_function(): void
    {
        $function = function (int $x) {
            return $x * $x;
        };

        $delayed = delay($function, 2);

        $this->assertEquals(4, $delayed());
    }
}
