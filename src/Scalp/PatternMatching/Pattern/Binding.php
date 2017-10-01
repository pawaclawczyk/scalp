<?php

declare(strict_types=1);

namespace Scalp\PatternMatching\Pattern;

interface Binding
{
    public function bind(): Pattern;
}
