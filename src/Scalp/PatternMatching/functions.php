<?php

declare(strict_types=1);

namespace Scalp\PatternMatching {
    function match($x): MatchSubject
    {
        return new UnresolvedMatchSubject($x);
    }

    function Any(): Any
    {
        return new Any();
    }

    function Value($value): Value
    {
        return new Value($value);
    }

    function Type(string $type, ...$args): Type
    {
        return new Type($type, ...$args);
    }

    const arguments = __NAMESPACE__.'\arguments';

    function arguments(...$xs): array
    {
        return $xs;
    }
}
