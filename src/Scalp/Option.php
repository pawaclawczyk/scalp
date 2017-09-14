<?php

declare(strict_types=1);

namespace Scalp;

use function Scalp\Type\restrictCallableReturnType;

abstract class Option
{
    abstract public function isEmpty(): bool;

    final public function isDefined(): bool
    {
        return !$this->isEmpty();
    }

    abstract public function get();

    final public function getOrElse($default)
    {
        return $this->isEmpty() ? $default : $this->get();
    }

    final public function orNull()
    {
        return $this->getOrElse(null);
    }

    final public function map(callable $f): Option
    {
        return $this->isEmpty() ? $this : Some($f($this->get()));
    }

    final public function fold(callable $ifEmpty, callable $f)
    {
        return $this->isEmpty() ? $ifEmpty() : $f($this->get());
    }

    final public function flatMap($f): Option
    {
        restrictCallableReturnType($f, self::class);

        return $this->isEmpty() ? $this : $f($this->get());
    }

    final public function flatten(): Option
    {
        return $this->isDefined() && ($this->get() instanceof self) ? $this->get()->flatten() : $this;
    }

    final public function filter(callable $p): Option
    {
        return ($this->isEmpty() || $p($this->get())) ? $this : None();
    }

    final public function filterNot(callable $p): Option
    {
        return ($this->isEmpty() || !$p($this->get())) ? $this : None();
    }

    final public function contains($elem): bool
    {
        return !$this->isEmpty() && $this->get() === $elem;
    }

    final public function exists(callable $p): bool
    {
        return !$this->isEmpty() && $p($this->get());
    }

    final public function forall(callable $p): bool
    {
        return $this->isEmpty() || $p($this->get());
    }
}
