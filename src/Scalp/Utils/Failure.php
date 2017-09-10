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

    public function foreach(callable $f): void
    {
    }

    public function flatMap(callable $f): TryCatch
    {
        return $this;
    }

    public function map(callable $f): TryCatch
    {
        return $this;
    }

    public function filter(callable $p): TryCatch
    {
        return $this;
    }

    public function recoverWith(callable $pf): TryCatch
    {
        restrictCallableReturnType($pf, TryCatch::class);

        try {
            return $pf($this->error);
        } catch (\Throwable $error) {
            return Failure($error);
        }
    }

    public function recover(callable $pf): TryCatch
    {
        try {
            return Success($pf($this->error));
        } catch (\Throwable $error) {
            return Failure($error);
        }
    }

    public function flatten(): TryCatch
    {
        return $this;
    }

    public function failed(): TryCatch
    {
        return Success($this->error);
    }

    public function transform(callable $s, callable $f): TryCatch
    {
        return $this->recoverWith($f);
    }

    public function __toString(): string
    {
        return sprintf('Failure[%s]("%s")', get_class($this->error), $this->error->getMessage());
    }
}
