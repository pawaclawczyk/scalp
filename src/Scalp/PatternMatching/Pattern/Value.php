<?php

declare(strict_types=1);

namespace Scalp\PatternMatching\Pattern;

use function Scalp\None;
use function Scalp\Option;
use Scalp\Option;

final class Value extends Pattern
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function match($x): Option
    {
        return is_object($x)
            ? ($this->value == $x ? Option([]) : None())
            : ($this->value === $x ? Option([]) : None());
    }
}
