<?php

declare(strict_types=1);

namespace Scalp\Utils {
    function delay(callable $functionOrCodeBlock, ...$args): Delayed
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
