<?php

declare(strict_types=1);

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

    function restrictCallableReturnType(callable $callable, string $expectedType): void
    {
        $rf = new \ReflectionFunction($callable);

        $valid = $rf->hasReturnType()
            ? checkType($rf->getReturnType()->getName(), $expectedType)
            : false;

        if (!$valid) {
            throw new \TypeError("Return value of callable must be defined and must have type $expectedType.");
        }
    }
}
