[![Build Status](https://travis-ci.org/pawaclawczyk/scalp.svg?branch=master)](https://travis-ci.org/pawaclawczyk/scalp)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/pawaclawczyk/scalp/badges/quality-score.png)](https://scrutinizer-ci.com/g/pawaclawczyk/scalp/)

Scalp
======

## Scalp\Conversion
### AnyToString
Converts any type to string. In case of value type looks for implicit conversion function and if not found casts to value string. 
In case of object type at first looks if object implements `toString` or `__toString` method, then looks for implicit conversion
and if none of then has been found return object hash id.

```php
echo AnyToString(null) . "\n";
echo AnyToString(false) . "\n";
echo AnyToString(36.6) . "\n";
echo AnyToString(printAny(new class { function toString(): string { return 'Hello World!'; }});) . "\n";
```

```bash
null
false
36.6
Hello World!
```

### Implicit conversion
Implicit conversion is a function that convert value of one type into another.

*Current version does not provide support for implicit conversion. Very simplified version is used by `AnyToString`.
Implicit conversion should have name following convention `[TypeA]To[TypeB]`.
In example in case of conversions able to convert value of some type to string, `AnyToString` will look for functions 
with name [Type]ToString.*

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
