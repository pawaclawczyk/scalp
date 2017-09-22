<?php

declare(strict_types=1);

namespace Scalp\Tests;

use PHPUnit\Framework\TestCase;
use Scalp\__;

final class PlaceholderMethodCallTest extends TestCase
{
    /** @test */
    public function it_creates_function_that_call_applied_objects_method()
    {
        $life = new class {
            public function answer(): int
            {
                return 42;
            }
        };

        $placeholder = __::answer();

        $this->assertEquals(42, $placeholder($life));
    }

    /** @test */
    public function it_throws_error_when_applied_object_does_not_have_method()
    {
        $life = new class {};

        $placeholder = __::answer();

        $this->expectException(\Error::class);
        $this->expectExceptionMessage('Call to undefined method class@anonymous::answer()');

        $placeholder($life);
    }
}
