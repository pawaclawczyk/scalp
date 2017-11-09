<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use function Scalp\Tuple;
use function Scalp\PatternMatching\match;
use function Scalp\PatternMatching\Type;
use function Scalp\PatternMatching\Any;
use function Scalp\println;
use Scalp\Tuple;

function tupleName(Tuple $tuple): string
{
    return match($tuple)
        ->case(Type(Tuple::class, Any()), function () { return 'Singleton'; })
        ->case(Type(Tuple::class, Any(), Any()), function () { return 'Pair'; })
        ->case(Type(Tuple::class, Any(), Any(), Any()), function () { return 'Triple'; })
        ->case(Type(Tuple::class), function () { return 'Other tuple'; })
        ->done();
}

$singleton = Tuple(1);
$pair = Tuple(1, 2);
$triple = Tuple(1, 2, 3);
$otherTuple = Tuple(1, 2, 3, 4);

println($pair); println(tupleName($pair));
println($singleton); println(tupleName($singleton));
println($otherTuple); println(tupleName($otherTuple));
println($triple); println(tupleName($triple));
