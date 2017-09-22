<?php

declare(strict_types=1);

namespace Scalp\Tests\PatternMatching;

use Scalp\PatternMatching\ResolvedMatchSubject;
use PHPUnit\Framework\TestCase;
use function Scalp\PatternMatching\Any;

final class ResolvedMatchSubjectTest extends TestCase
{
    /** @test */
    public function it_returns_value_from_this(): void
    {
        $result = (new ResolvedMatchSubject(42))
            ->case(Any()->bind(), function (int $x): int { return $x ** 2; })
            ->done();

        $this->assertEquals(42, $result);
    }
}
