<?php

declare(strict_types=1);

namespace Scalp\Reflection {
    function reflectionFunction(callable $f): \ReflectionFunctionAbstract
    {
        if (is_object($f)) {
            return (new \ReflectionObject($f))->getMethod('__invoke');
        }

        if (isClassStaticMethodCall($f)) {
            return (new \ReflectionClass($f[0]))->getMethod($f[1]);
        }

        if (isObjectMethodCall($f)) {
            return (new \ReflectionObject($f[0]))->getMethod($f[1]);
        }

        return new \ReflectionFunction($f);
    }

    function isClassStaticMethodCall(callable $f): bool
    {
        return isMethodCall($f) && is_string($f[0]);
    }

    function isObjectMethodCall(callable $f): bool
    {
        return isMethodCall($f) && is_object($f[0]);
    }

    function isMethodCall(callable $f): bool
    {
        return is_array($f) && is_string($f[1]);
    }
}
