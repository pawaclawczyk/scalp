<?php

declare(strict_types=1);

namespace {
    require_once __DIR__.'/Conversion/implicit_conversion.php';
    require_once __DIR__.'/Type/restrictions.php';
}

namespace Scalp {
    use function Scalp\Conversion\AnyToString;

    function println($x): void
    {
        echo AnyToString($x)."\n";
    }

    function None(): None
    {
        return new None();
    }

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
    function Delayed(callable $functionOrCodeBlock, ...$args): Delayed
    {
        return new Delayed($functionOrCodeBlock, ...$args);
    }

    function Failure(\Throwable $error): Failure
    {
        return new Failure($error);
    }

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

    function type($x): string
    {
        return is_object($x)
                ? get_class($x)
                : gettype($x)
            ;
    }

    function checkType(string $actual, string $expected): bool
    {
        return $actual === $expected
                || is_subclass_of($actual, $expected, true)
            ;
    }
}
