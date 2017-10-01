<?php

declare(strict_types=1);

namespace Scalp\PatternMatching\Pattern;

use Scalp\Option;

abstract class Pattern
{
    abstract public function match($x): Option;
}
