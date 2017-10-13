<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use function Scalp\curry;
use function Scalp\println;

$match = curry('preg_match');

$containsFoo = $match('/foo/');
$containsBar = $match('/bar/');

println($containsFoo('foobar'));
println($containsFoo('foofoo'));
println($containsFoo('barbar'));

println($containsBar('foobar'));
println($containsBar('foofoo'));
println($containsBar('barbar'));
