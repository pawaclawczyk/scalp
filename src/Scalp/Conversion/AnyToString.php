<?php

declare(strict_types=1);

namespace Scalp\Conversion;

final class AnyToString
{
    public function __invoke($any): string
    {
        return is_object($any)
                ? $this->objectToString($any)
                : $this->valueToString($any);
    }

    private function objectToString($object): string
    {
        $ro = new \ReflectionObject($object);

        if ($ro->hasMethod('toString')) {
            return $object->toString();
        }

        if ($ro->hasMethod('__toString')) {
            return (string) $object;
        }

        return spl_object_hash($object);
    }

    private function valueToString($value): string
    {
        if ($value === null) {
            return 'null';
        }

        if ($value === true) {
            return 'true';
        }

        if ($value === false) {
            return 'false';
        }

        if (is_array($value)) {
            $valuesAsString = implode(', ', array_map(AnyToString, $value));

            return "Array($valuesAsString)";
        }

        return (string) $value;
    }
}
