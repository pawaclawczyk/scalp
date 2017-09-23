<?php

declare(strict_types=1);

namespace {
    require_once __DIR__.'/Conversion/implicit_conversion.php';
    require_once __DIR__.'/PatternMatching/functions.php';
    require_once __DIR__.'/Reflection/functions.php';
    require_once __DIR__.'/Type/restrictions.php';
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

namespace Scalp\Utils {
    function delayed(callable $functionOrCodeBlock, ...$args): Delayed
    {
        return new Delayed($functionOrCodeBlock, ...$args);
    }

    const Failure = __NAMESPACE__.'\Failure';

    function Failure(\Throwable $error): Failure
    {
        return new Failure($error);
    }

    const Success = __NAMESPACE__.'\Success';

    function Success($value): Success
    {
        return new Success($value);
    }

    function TryCatch(callable $delayed): TryCatch
    {
        try {
            return new Success($delayed());
        } catch (\Throwable $e) {
            return new Failure($e);
        }
    }

    const isInstanceOfType = __NAMESPACE__.'\isInstanceOfType';

    function isInstanceOfType($value, string $type): bool
    {
        return checkType(type($value), $type);
    }

    function type($x): string
    {
        return is_object($x)
                ? get_class($x)
                : gettype($x)
            ;
    }

    const checkType = __NAMESPACE__.'\checkType';

    function checkType(string $actual, string $expected): bool
    {
        return $actual === $expected
                || is_subclass_of($actual, $expected, true)
            ;
    }
}
