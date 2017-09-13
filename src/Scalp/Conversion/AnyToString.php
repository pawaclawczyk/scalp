<?php

declare(strict_types=1);

namespace Scalp\Conversion;

use function Scalp\Utils\type;

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
        $conversion = $this->implicitConversionFor($value);

        return  ($conversion !== '')
            ? $conversion($value)
            : (string) $value;
    }

    private function implicitConversionFor($any): string
    {
        $conversion = type($any).'ToString';

        foreach (get_defined_functions()['user'] as $function) {
            if (preg_match("/\\\\$conversion\$/i", $function)) {
                return $function;
            }
        }

        return '';
    }
}
