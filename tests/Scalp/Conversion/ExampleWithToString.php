<?php

declare(strict_types=1);

namespace Scalp\Tests\Conversion;

final class ExampleWithToString
{
    private $s;

    public function __construct(string $s)
    {
        $this->s = $s;
    }

    public function toString(): string
    {
        return $this->s;
    }
}
