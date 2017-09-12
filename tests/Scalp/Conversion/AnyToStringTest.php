<?php

declare(strict_types=1);

namespace Scalp\Tests\Conversion;

use PHPUnit\Framework\TestCase;
use function Scalp\Conversion\AnyToString;

class AnyToStringTest extends TestCase
{
    /** @test */
    public function it_converts_null_to_string_null(): void
    {
        $this->assertEquals('null', AnyToString(null));
    }

    /** @test */
    public function it_converts_true_to_string_true(): void
    {
        $this->assertEquals('true', AnyToString(true));
    }

    /** @test */
    public function it_converts_false_to_string_false(): void
    {
        $this->assertEquals('false', AnyToString(false));
    }

    /** @test */
    public function it_converts_integer_to_string(): void
    {
        $this->assertEquals('12345', AnyToString(12345));
    }

    /** @test */
    public function it_converts_double_to_string(): void
    {
        $this->assertEquals('12345.6789', AnyToString(12345.6789));
    }

    /** @test */
    public function it_converts_empty_array_to_string(): void
    {
        $this->assertEquals('Array()', AnyToString([]));
    }

    /** @test */
    public function it_converts_array_with_integers_to_string(): void
    {
        $this->assertEquals('Array(1, 2, 3, 4, 5)', AnyToString([1, 2, 3, 4, 5]));
    }

    /** @test */
    public function it_converts_object_with_to_string_method_to_string(): void
    {
        $this->assertEquals('Hello World!', AnyToString(new ExampleWithToString('Hello World!')));
    }

    /** @test */
    public function it_converts_object_with_magic_to_string_method_to_string(): void
    {
        $this->assertEquals('Hello World!', AnyToString(new ExampleWithMagicToString('Hello World!')));
    }

    /** @test */
    public function it_converts_object_to_its_hash_id(): void
    {
        $exampleWithoutConversionToString = new ExampleWithoutConversionToString();

        $objectHashId = spl_object_hash($exampleWithoutConversionToString);

        $this->assertEquals($objectHashId, AnyToString($exampleWithoutConversionToString));
    }
}
