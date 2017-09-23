<?php

declare(strict_types=1);

namespace Scalp\PatternMatching;

use const Scalp\__;
use Scalp\Option;
use function Scalp\papply;

final class Bound extends Pattern
{
    private $inner;

    public function __construct(Pattern $inner)
    {
        $this->inner = $inner;
    }

    public function match($x): Option
    {
        return $this->inner
            ->match($x)
            ->map(papply('array_merge', [$x], __));
    }
}
