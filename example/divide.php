<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use function Scalp\Utils\delay;
use function Scalp\Utils\TryCatch;
use Scalp\Utils\TryCatch;

assert_options(ASSERT_ACTIVE, 1);
assert_options(ASSERT_EXCEPTION, 1);

function readInt(string $prompt): int
{
    $input = readline($prompt);

    assert($input === strval(intval($input)), "String '$input' cannot be converted to Int.");

    return intval($input);
}

$dividend = TryCatch(delay('readInt', "Enter an Int that you'd like to divide: "));
$divisor = TryCatch(delay('readInt', "Enter an Int that you'd like to divide by: "));

$result = $dividend->flatMap(function (int $x) use ($divisor): TryCatch {
    return $divisor->map(function (int $y) use ($x) {
        return intdiv($x, $y);
    });
});

echo $result."\n";
