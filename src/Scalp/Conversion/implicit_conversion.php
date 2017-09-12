<?php

declare(strict_types=1);

namespace Scalp\Conversion {
    function NullToString(): string
    {
        return 'null';
    }

    function BooleanToString(bool $boolean): string
    {
        return $boolean ? 'true' : 'false';
    }

    function ArrayToString(array $array): string
    {
        $valuesAsString = implode(', ', array_map(AnyToString, $array));

        return "Array($valuesAsString)";
    }
}
