<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use Scalp\Collection\Tuple;
use function Scalp\PatternMatching\match;
use function Scalp\PatternMatching\Type;
use function Scalp\PatternMatching\Any;
use function Scalp\println;

function tupleName(Tuple $tuple): string
{
    return match($tuple)
        ->case(Type(Tuple::class, Any()), function () { return 'Singleton'; })
        ->case(Type(Tuple::class, Any(), Any()), function () { return 'Pair'; })
        ->case(Type(Tuple::class, Any(), Any(), Any()), function () { return 'Triple'; })
        ->case(Type(Tuple::class), function () { return 'Other tuple'; })
        ->done();
}

//println(tupleName(new Tuple(1, 2)));
println(tupleName(new Tuple(1)));
//println(tupleName(new Tuple(1, 2, 3, 4)));
//println(tupleName(new Tuple(1, 2, 3)));
