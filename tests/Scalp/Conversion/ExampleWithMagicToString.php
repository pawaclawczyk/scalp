<?php

declare(strict_types=1);

namespace Scalp\Tests\Conversion;

final class ExampleWithMagicToString
{
    private $s;

    public function __construct(string $s)
    {
        $this->s = $s;
    }

    public function __toString(): string
    {
        return $this->s;
    }
}
