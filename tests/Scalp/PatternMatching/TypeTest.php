<?php

declare(strict_types=1);

namespace Scalp\Tests\PatternMatching;

use function Scalp\None;
use Scalp\PatternMatching\CaseClass;
use Scalp\PatternMatching\Deconstruction;
use Scalp\PatternMatching\Exception\InvalidPatternsNumber;
use function Scalp\PatternMatching\Type;
use PHPUnit\Framework\TestCase;
use function Scalp\Some;
use Scalp\Some;
use function Scalp\PatternMatching\Any;
use Scalp\None;

final class TypeTest extends TestCase
{
    /** @test */
    public function it_matches_value_by_type(): void
    {
        $this->assertEquals(
            Some([]),
            Type(Subject::class)
                ->match(new Subject())
        );

        $this->assertEquals(
            Some([]),
            Type(Subject::class)
                ->match(new Subject(1, 2, 3))
        );
    }

    /** @test */
    public function it_matches_value_by_primitive_type(): void
    {
        $this->assertInstanceOf(Some::class, Type('integer')->match(42));

        $this->assertInstanceOf(None::class, Type('integer')->match('42'));
    }

    /** @test */
    public function it_does_not_match_value_of_other_type(): void
    {
        $this->assertEquals(
            None(),
            Type(Subject::class)
                ->match(new class() implements CaseClass {
                    use Deconstruction;
                })
        );
    }

    /** @test */
    public function it_matches_type_arguments_with_following_patterns(): void
    {
        $this->assertEquals(
            Some([]),
            Type(Subject::class, Type(Some::class))
                ->match(new Subject(Some(42)))
        );
    }

    /** @test */
    public function it_does_not_match_when_type_arguments_do_not_match_following_patterns(): void
    {
        $this->assertEquals(
            None(),
            Type(Subject::class, Type(Some::class))
                ->match(new Subject(None()))
        );
    }

    /** @test */
    public function it_binds_value(): void
    {
        $this->assertEquals(
            Some([new Subject(1, 2, 3)]),
            Type(Subject::class)->bind()
                ->match(new Subject(1, 2, 3))
        );
    }

    /** @test */
    public function it_returns_all_bound_values(): void
    {
        $this->assertEquals(
            Some([new Subject(1, 2, 3), 1, 3]),
            Type(
                Subject::class,
                Any()->bind(),
                Any(),
                Any()->bind()
            )->bind()
                ->match(new Subject(1, 2, 3))
        );
    }

    /** @test */
    public function it_requires_patterns_for_all_constructor_arguments(): void
    {
        $this->expectException(InvalidPatternsNumber::class);
        $this->expectExceptionMessage('Instance of type "Scalp\Tests\PatternMatching\Subject" was created with 2 arguments, but here is 1 patterns provided.');

        Type(Subject::class, Type('integer'))
            ->match(new Subject(1, 2));
    }
}
