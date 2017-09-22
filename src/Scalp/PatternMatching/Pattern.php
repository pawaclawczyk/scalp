<?php

declare(strict_types=1);

namespace Scalp\PatternMatching;

use Scalp\Option;

abstract class Pattern
{
    abstract public function match($x): Option;
}
