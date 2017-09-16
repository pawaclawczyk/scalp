<?php

declare(strict_types=1);

namespace Scalp\Example {
    use function Scalp\None;
    use function Scalp\Option;
    use Scalp\Option;
    use function Scalp\println;
    use function Scalp\Some;

    require_once __DIR__.'/../vendor/autoload.php';

    function divide(int $x, int $y): Option
    {
        return $y === 0 ? None() : Some(intdiv($x, $y));
    }

    println(divide(42, 6));
    println(divide(42, 0));

    $option = Option(42);

    $square = function (int $x): int {
        return $x ** 2;
    };

    println($option->map($square));

    $isOdd = function (int $x): bool {
        return $x % 2 === 1;
    };

    println($option->filter($isOdd));

    $squareRoot = function (int $x): Option {
        return $x >= 0 ? Some(sqrt($x)) : None();
    };

    println($option->flatMap($squareRoot));

    println(None()->map($square));
    println(None()->filter($isOdd));
    println(None()->flatMap($squareRoot));
}
