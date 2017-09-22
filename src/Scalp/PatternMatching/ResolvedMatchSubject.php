<?php

declare(strict_types=1);

namespace Scalp\PatternMatching;

final class ResolvedMatchSubject extends MatchSubject
{
    public function case(Pattern $pattern, callable $handler): MatchSubject
    {
        return $this;
    }

    public function done()
    {
        return $this->get();
    }
}
