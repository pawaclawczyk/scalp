<?php

declare(strict_types=1);

namespace Scalp\PatternMatching;

use Scalp\PatternMatching\Exception\PatternMatchingSubjectNotFound;
use Scalp\PatternMatching\Pattern\Pattern;

final class UnresolvedMatchSubject extends MatchSubject
{
    public function case(Pattern $pattern, callable $handler): MatchSubject
    {
        return $pattern
            ->match($this->get())
            ->fold(
                function (): MatchSubject { return $this; },
                function ($result) use ($handler): MatchSubject { return new ResolvedMatchSubject($handler(...$result)); }
            );
    }

    public function done(): void
    {
        throw PatternMatchingSubjectNotFound::for($this->get());
    }
}
