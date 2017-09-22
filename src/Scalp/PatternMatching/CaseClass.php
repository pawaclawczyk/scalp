<?php

declare(strict_types=1);

namespace Scalp\PatternMatching;

interface CaseClass
{
    public function deconstruct(): array;
}
