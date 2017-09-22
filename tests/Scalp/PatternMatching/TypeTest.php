<?php

declare(strict_types=1);

namespace Scalp\Tests\PatternMatching;

use function Scalp\None;
use Scalp\PatternMatching\CaseClass;
use Scalp\PatternMatching\Deconstruction;
use function Scalp\PatternMatching\Type;
use PHPUnit\Framework\TestCase;
use function Scalp\Some;
use Scalp\Some;
use function Scalp\PatternMatching\Any;

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
}
