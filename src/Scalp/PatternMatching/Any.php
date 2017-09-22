<?php

declare(strict_types=1);

namespace Scalp\PatternMatching;

use Scalp\Option;
use function Scalp\Some;

final class Any extends Pattern
{
    public function match($x): Option
    {
        return Some([]);
    }

    public function bind(): Pattern
    {
        return new Bound($this);
    }
}
