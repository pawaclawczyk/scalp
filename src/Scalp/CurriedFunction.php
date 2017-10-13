<?php

declare(strict_types=1);

namespace Scalp;

use function Scalp\Reflection\reflectionFunction;

final class CurriedFunction
{
    private $f;
    private $arity;
    private $args;

    public static function lift(callable $f): CurriedFunction
    {
        return new self($f, reflectionFunction($f)->getNumberOfRequiredParameters(), []);
    }

    public static function liftN(callable $f, int $arity): CurriedFunction
    {
        $requiredArity = reflectionFunction($f)->getNumberOfRequiredParameters();

        if ($arity < $requiredArity) {
            throw new \ArgumentCountError(sprintf('Declared arity of function is %d, but required number of arguments is %d.', $arity, $requiredArity));
        }

        return new self($f, $arity, []);
    }

    public function __invoke(...$args)
    {
        $callArgs = array_merge($this->args, $args);

        if (\count($callArgs) >= $this->arity) {
            return ($this->f)(...$callArgs);
        }

        return new self($this->f, $this->arity, $callArgs);
    }

    private function __construct(callable $f, int $arity, array $args)
    {
        $this->f = $f;
        $this->arity = $arity;
        $this->args = $args;
    }
}
