<?php

declare(strict_types=1);

namespace Scalp\Type {
    use function Scalp\Utils\checkType;

    function restrictCallableReturnType(callable $callable, string $expectedType): void
    {
        $rf = is_object($callable)
            ? (new \ReflectionObject($callable))->getMethod('__invoke')
            : new \ReflectionFunction($callable);

        $valid = $rf->hasReturnType()
            ? checkType($rf->getReturnType()->getName(), $expectedType)
            : false;

        if (!$valid) {
            throw new \TypeError("Return value of callable must be defined and must have type $expectedType.");
        }
    }
}
