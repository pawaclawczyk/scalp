<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use function Scalp\PatternMatching\match;
use function Scalp\PatternMatching\Any;
use function Scalp\PatternMatching\Value;
use function Scalp\PatternMatching\Type;
use function Scalp\println;
use function Scalp\Some;
use Scalp\None;
use Scalp\Some;

function returnString(string $s): callable
{
    return function () use ($s): string {
        return $s;
    };
}

$res0 = match(42)
    ->case(Any(), function (): string { return 'Anything'; })
    ->done();

println($res0);

$res1 = match(Some(42))
    ->case(Any(), function (): string { return 'Anything'; })
    ->done();

println($res1);

$res2 = match(42)
    ->case(Value(13), function (): string { return 'Number 13'; })
    ->case(Value('42'), function (): string { return 'String "42"'; })
    ->case(Value(42), function (): string { return 'Number 42'; })
    ->case(Any(), function (): string { return 'Fallback'; })
    ->done();

println($res2);

$res3 = match(Some(42))
    ->case(Value(Some(13)), function (): string { return 'Some 13'; })
    ->case(Value(Some(42)), function (): string { return 'Some 42'; })
    ->case(Any(), function (): string { return 'Fallback'; })
    ->done();

println($res3);

$res4 = match(Some(42))
    ->case(Value(Some(13)), function (): string { return 'Some 13'; })
    ->case(Value(Some('42')), function (): string { return 'Some 42'; })
    ->case(Any(), function (): string { return 'Fallback'; })
    ->done();

println($res4);

$res5 = match(42)
    ->case(Type('string'), function (): string { return 'String'; })
    ->case(Type('integer'), function (): string { return 'Integer'; })
    ->case(Any(), function (): string { return 'Not integer'; })
    ->done();

println($res5);

$res6 = match(Some(42))
    ->case(Type(None::class), function (): string { return 'None'; })
    ->case(Type(Some::class), function (): string { return 'Some'; })
    ->case(Any(), function (): string { return 'Neither'; })
    ->done();

println($res6);

$res7 = match(Some(42))
    ->case(Type(Some::class, Value('42')), returnString('Inner value is string'))
    ->case(Type(Some::class, Value(42)), returnString('Inner value is integer'))
    ->case(Any(), returnString('Fallback'))
    ->done();

println($res7);
