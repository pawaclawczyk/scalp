Scalp
======

## Scalp\Utils
### Delayed
The `Delayed` type represents function and its arguments without invoking the function.
The inner function can be executed by invoking the `Delayed` wrapper.

```php
use function Scalp\Utils\Delayed;

$delayed = Delayed(function (int $x): int { return $x * $x; }, 2);

echo $delayed();
```

```bash
4
```

### TryCatch
The `TryCatch` type represents computation that may either result in an exception, or return successful value.

```php
use function Scalp\Utils\Delayed;
use function Scalp\Utils\TryCatch;

$computation = function (int $divisor): int {
    return intdiv(42, $divisor);
};

$success = TryCatch(Delayed($computation, 7));
$failure = TryCatch(Delayed($computation, 0));

echo "Success: $success\n";
echo "Failure: $failure\n";
```

```bash
Success: Success[integer](6)
Failure: Failure[DivisionByZeroError]("Division by zero")
```
