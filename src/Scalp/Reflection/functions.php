<?php

declare(strict_types=1);

namespace Scalp\Reflection {
    function reflectionFunction(callable $f): \ReflectionFunctionAbstract
    {
        if (is_object($f)) {
            return (new \ReflectionObject($f))->getMethod('__invoke');
        }

        if (is_array($f) && is_string($f[0]) && is_string($f[1])) {
            return (new \ReflectionClass($f[0]))->getMethod($f[1]);
        }

        if (is_array($f) && is_object($f[0]) && is_string($f[1])) {
            return (new \ReflectionObject($f[0]))->getMethod($f[1]);
        }

        return new \ReflectionFunction($f);
    }
}
