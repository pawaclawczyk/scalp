<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use function Scalp\Conversion\AnyToString;

function printAny($any): void
{
    echo AnyToString($any)."\n";
}

printAny(null);
printAny(true);
printAny(false);
printAny(42);
printAny(36.6);
printAny('Hello World!');
printAny([]);
printAny([1, 2, 3]);
printAny([['X', 'O', 'O'], ['O', 'X', 'O'], ['O', 'O', 'X']]);
printAny(new class() {
});
printAny(new class() {
    public function toString(): string
    {
        return 'Hello World!';
    }
});
