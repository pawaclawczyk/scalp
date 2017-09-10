Scalp
======

## 1. Delayed
Delay function call by converting it with its future arguments into niladic callable.

```php
<?php
    use function Scalp\Utils\Delayed;
    $delayed = Delayed(function (int $x): int { return $x * $x; }, 2);
    echo $delayed();
```

```bash
4
```

## 2. TryCatch
The `TryCatch` type represents computation that may either result in an exception, or return successful value.

```php
<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use function Scalp\Utils\Delayed;
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

$dividend = TryCatch(Delayed('readInt', "Enter an Int that you'd like to divide: "));
$divisor = TryCatch(Delayed('readInt', "Enter an Int that you'd like to divide by: "));

$result = $dividend->flatMap(function (int $x) use ($divisor): TryCatch {
    return $divisor->map(function (int $y) use ($x) {
        return intdiv($x, $y);
    });
});

echo $result."\n";

```

```bash
$ php example/divide.php
Enter an Int that you'd like to divide: 12
Enter an Int that you'd like to divide by: 4
Success[integer](3)
```

```bash
$ php example/divide.php
Enter an Int that you'd like to divide: 12
Enter an Int that you'd like to divide by: four
Failure[AssertionError]("String 'four' cannot be converted to Int.")
```

```bash
$ php example/divide.php
Enter an Int that you'd like to divide: 12
Enter an Int that you'd like to divide by: 0
Failure[DivisionByZeroError]("Division by zero")
```
