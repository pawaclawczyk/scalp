<?php

declare(strict_types=1);

namespace Scalp\PatternMatching;

trait Deconstruction
{
    private $args = [];

    protected function construct(...$args): void
    {
        $this->args = $args;
    }

    public function deconstruct(): array
    {
        return $this->args;
    }
}
