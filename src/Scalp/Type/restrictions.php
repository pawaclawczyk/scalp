<?php

declare(strict_types=1);

namespace Scalp\Type {
    use function Scalp\Reflection\reflectionFunction;
    use function Scalp\Utils\checkType;

    function restrictCallableReturnType(callable $f, string $expectedType): void
    {
        $rf = reflectionFunction($f);

        $valid = $rf->hasReturnType()
            ? checkType((string) $rf->getReturnType(), $expectedType)
            : false;

        if (!$valid) {
            throw new \TypeError("Return value of callable must be defined and must have type $expectedType.");
        }
    }
}
