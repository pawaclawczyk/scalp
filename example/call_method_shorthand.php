<?php

declare(strict_types=1);

namespace Scalp\Example {
    use function Scalp\println;
    use function Scalp\Some;

    require_once __DIR__.'/../vendor/autoload.php';

    class Counter
    {
        private $current;

        public static function zero(): Counter
        {
            return new self(0);
        }

        public function increment(int $step = 1): Counter
        {
            return new self($this->current + $step);
        }

        public function toString(): string
        {
            return "Counter({$this->current})";
        }

        private function __construct(int $from)
        {
            $this->current = $from;
        }
    }

    final class __
    {
        public static function __callStatic(string $name, array $arguments): callable
        {
            return function ($x) use ($name, $arguments) {
                return $x->{$name}(...$arguments);
            };
        }
    }

    $res0 = Some(Counter::zero())
        ->map(__::increment())
        ->map(__::increment())
    ;

    println($res0);

    $res1 = $res0
        ->map(__::increment(3))
    ;

    println($res1);
}
