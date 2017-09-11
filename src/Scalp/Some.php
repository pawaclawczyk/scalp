<?php

declare(strict_types=1);

namespace Scalp;

final class Some extends Option
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function isEmpty(): bool
    {
        return false;
    }

    public function get()
    {
        return $this->value;
    }
}
