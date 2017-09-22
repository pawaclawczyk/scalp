<?php

declare(strict_types=1);

namespace Scalp\Tests\PatternMatching;

use Scalp\None;
use Scalp\PatternMatching\PatternMatchingSubjectNotFound;
use function Scalp\PatternMatching\Type;
use Scalp\PatternMatching\UnresolvedMatchSubject;
use PHPUnit\Framework\TestCase;
use function Scalp\PatternMatching\Any;
use function Scalp\Some;
use Scalp\Some;

final class UnresolvedMatchSubjectTest extends TestCase
{
    /** @test */
    public function it_returns_value_from_handler(): void
    {
        $result = (new UnresolvedMatchSubject(42))
            ->case(Any()->bind(), function (int $x): int { return $x ** 2; })
            ->done();

        $this->assertEquals(1764, $result);
    }

    /** @test */
    public function it_returns_value_from_handler_matching_subject(): void
    {
        $result = (new UnresolvedMatchSubject(Some(42)))
            ->case(Type(None::class), function () { return 'None'; })
            ->case(Type(Some::class), function () { return 'Some'; })
            ->case(Any(), function () { return 'Any'; })
            ->done();

        $this->assertEquals('Some', $result);
    }

    /** @test */
    public function it_returns_value_from_first_handler_matching_subject(): void
    {
        $result = (new UnresolvedMatchSubject(Some(42)))
            ->case(Type(None::class), function () { return 'None'; })
            ->case(Any(), function () { return 'Any'; })
            ->case(Type(Some::class), function () { return 'Some'; })
            ->done();

        $this->assertEquals('Any', $result);
    }

    /** @test */
    public function it_throws_pattern_matching_subject_not_found_when_none_pattern_matches_subject(): void
    {
        $this->expectException(PatternMatchingSubjectNotFound::class);
        $this->expectExceptionMessage('Patter matching subject "42" is not defined in this match expression".');

        (new UnresolvedMatchSubject(42))
            ->done();
    }
}
