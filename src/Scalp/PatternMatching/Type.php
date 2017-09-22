<?php

declare(strict_types=1);

namespace Scalp\PatternMatching;

use function Scalp\None;
use Scalp\Option;
use function Scalp\Some;

final class Type extends Pattern
{
    private $type;
    private $patterns;

    public function __construct(string $type, Pattern ...$patterns)
    {
        $this->type = $type;
        $this->patterns = $patterns;
    }

    public function match($x): Option
    {
        if (!$x instanceof CaseClass) {
            throw new \RuntimeException('Argument must be CaseClass');
        }

        if (!$x instanceof $this->type) {
            return None();
        }

        if (empty($this->patterns)) {
            return Some([]);
        }

        $arguments = $x->deconstruct();

        $currentPattern = 0;

        $values = [];

        foreach ($arguments as $argument) {
            $pattern = $this->patterns[$currentPattern];

            $res = $pattern->match($argument);

            if ($res->isEmpty()) {
                return None();
            }

            $value = $res->get();

            $values = array_merge($values, is_array($value) ? $value : [$value]);

            $currentPattern = $currentPattern + 1;
        }

        return Some($values);
    }

    public function bind()
    {
        return new Bound($this);
    }
}
