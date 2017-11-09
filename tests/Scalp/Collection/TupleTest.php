<?php

declare(strict_types=1);

namespace Scalp\Tests\Collection;

use function Scalp\Pair;
use function Scalp\Some;
use function Scalp\Tuple;
use PHPUnit\Framework\TestCase;
use Scalp\Exception\NoSuchElementException;

final class TupleTest extends TestCase
{
    /** @test */
    public function it_holds_two_elements(): void
    {
        $pair = Pair('life', 42);

        $this->assertEquals('life', $pair->_1);
        $this->assertEquals(42, $pair->_2);
    }

    /** @test */
    public function it_throws_no_such_element_when_element_does_not_exist(): void
    {
        $pair = Tuple('life', 42);

        $this->expectException(NoSuchElementException::class);
        $this->expectExceptionMessage('Tuple->_3');

        $pair->_3;
    }

    /** @test */
    public function it_throws_no_such_element_when_accessing_not_indexed_property(): void
    {
        $pair = Tuple('life', 42);

        $this->expectException(NoSuchElementException::class);
        $this->expectExceptionMessage('Tuple->property');

        $pair->property;
    }

    /** @test */
    public function it_throws_invalid_argument_exception_when_construct_wit_zero_elements(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Tuple must by construct with at least one element.');

        Tuple();
    }

    /** @test */
    public function it_converts_to_string(): void
    {
        $this->assertEquals(
            'Tuple(42, true, hello, Some[integer](1764))',
            (string) Tuple(42, true, 'hello', Some(1764))
        );
    }
}
