<?php

declare(strict_types=1);

namespace Scalp\Tests\Utils;

class Example
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
