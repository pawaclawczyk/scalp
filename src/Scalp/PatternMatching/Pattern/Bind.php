<?php

declare(strict_types=1);

namespace Scalp\PatternMatching\Pattern;

trait Bind
{
    public function bind(): Pattern
    {
        return new Bound($this);
    }
}
