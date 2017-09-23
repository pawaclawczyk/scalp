<?php

declare(strict_types=1);

namespace Scalp\Tests\Collection;

use Scalp\Collection\Tuple;
use PHPUnit\Framework\TestCase;
use Scalp\Exception\NoSuchElementException;

final class TupleTest extends TestCase
{
    /** @test */
    public function it_holds_two_elements(): void
    {
        $pair = new Tuple('life', 42);

        $this->assertEquals('life', $pair->_1);
        $this->assertEquals(42, $pair->_2);
    }

    /** @test */
    public function it_throws_no_such_element_when_element_does_not_exist(): void
    {
        $pair = new Tuple('life', 42);

        $this->expectException(NoSuchElementException::class);
        $this->expectExceptionMessage('Tuple->_3');

        $pair->_3;
    }

    /** @test */
    public function it_throws_no_such_element_when_accessing_not_indexed_property(): void
    {
        $pair = new Tuple('life', 42);

        $this->expectException(NoSuchElementException::class);
        $this->expectExceptionMessage('Tuple->property');

        $pair->property;
    }
}
