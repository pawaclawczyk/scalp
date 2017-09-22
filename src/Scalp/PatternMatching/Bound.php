<?php

declare(strict_types=1);

namespace Scalp\PatternMatching;

use Scalp\None;
use Scalp\Option;
use function Scalp\Some;

final class Bound extends Pattern
{
    private $inner;

    public function __construct(Pattern $inner)
    {
        $this->inner = $inner;
    }

    public function match($x): Option
    {
        $result = $this->inner->match($x);

        if ($result instanceof None) {
            return $result;
        }

        $args = array_merge([$x], $result->get());

        return Some($args);
    }
}
