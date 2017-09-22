<?php

declare(strict_types=1);

namespace Scalp;

final class __
{
    public const __ = __;

    public static function __callStatic($name, $arguments)
    {
        return function ($o) use ($name) {
            return $o->$name();
        };
    }
}
