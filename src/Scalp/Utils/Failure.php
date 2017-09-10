<?php

declare(strict_types=1);

namespace Scalp\Utils;

final class Failure extends TryCatch
{
    private $error;

    public function __construct(\Throwable $error)
    {
        $this->error = $error;
    }

    public function isFailure(): bool
    {
        return true;
    }

    public function isSuccess(): bool
    {
        return false;
    }

    public function getOrElse($default)
    {
        return $default;
    }

    public function orElse(TryCatch $default): TryCatch
    {
        return $default;
    }

    public function get(): void
    {
        throw $this->error;
    }

    public function flatMap(callable $f): TryCatch
    {
        return $this;
    }

    public function map(callable $f): TryCatch
    {
        return $this;
    }

    public function __toString(): string
    {
        return sprintf('Failure[%s]("%s")', get_class($this->error), $this->error->getMessage());
    }
}
