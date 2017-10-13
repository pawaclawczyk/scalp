<?php

declare(strict_types=1);

namespace {
    require_once __DIR__.'/Conversion/implicit_conversion.php';
    require_once __DIR__.'/PatternMatching/functions.php';
    require_once __DIR__.'/Reflection/functions.php';
    require_once __DIR__.'/Type/restrictions.php';
    require_once __DIR__.'/Utils/functions.php';
}

namespace Scalp {
    use function Scalp\Conversion\AnyToString;

    const identity = __NAMESPACE__.'\identity';

    function identity($x)
    {
        return $x;
    }

    const concat = __NAMESPACE__.'\concat';

    function concat(string ...$strings): string
    {
        return implode('', $strings);
    }

    const inc = __NAMESPACE__.'\inc';

    function inc(int $x, int $step = 1): int
    {
        return $x + $step;
    }

    const throwE = __NAMESPACE__.'\throwE';

    function throwE(string $class, string $message): void
    {
        throw new $class($message);
    }

    const println = __NAMESPACE__.'\println';

    function println($x): void
    {
        echo AnyToString($x)."\n";
    }

    const None = __NAMESPACE__.'\None';

    function None(): None
    {
        return new None();
    }

    const Some = __NAMESPACE__.'\Some';

    function Some($x): Some
    {
        return new Some($x);
    }

    function Option($x): Option
    {
        return ($x === null) ? None() : Some($x);
    }

    const __ = '$argument$';

    function papply(callable $f, ...$args): callable
    {
        return new PartialApplication($f, $args);
    }

    function Pair($_1, $_2): Tuple
    {
        return new Tuple($_1, $_2);
    }

    function Tuple(...$elements): Tuple
    {
        return new Tuple(...$elements);
    }

    function curry(callable $f): callable
    {
        return CurriedFunction::lift($f);
    }

    function curryN(callable $f, int $arity): callable
    {
        return CurriedFunction::liftN($f, $arity);
    }
}

namespace Scalp\Conversion {
    const AnyToString = __NAMESPACE__.'\AnyToString';

    function AnyToString($any): string
    {
        static $anyToString = null;

        if ($anyToString === null) {
            $anyToString = new AnyToString();
        }

        return $anyToString($any);
    }
}
