<?php

declare(strict_types=1);

namespace Scalp\PatternMatching;

abstract class MatchSubject
{
    private $x;

    public function __construct($x)
    {
        $this->x = $x;
    }

    protected function get()
    {
        return $this->x;
    }

    abstract public function case(Pattern $pattern, callable $handler): MatchSubject;

    abstract public function done();
}
