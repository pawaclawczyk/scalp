<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use function Scalp\papply;
use function Scalp\Conversion\AnyToString;
use function Scalp\println;
use const Scalp\__;

$isEven = function (int $x): bool {
    return $x % 2 === 0;
};

$filterEven = papply(array_filter, __, $isEven);

println(AnyToString(
    $filterEven([-2, -1, 0, 1, 2])
));

println(AnyToString(
    $filterEven([11, 13, 17, 19])
));
