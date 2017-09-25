[![Build Status](https://travis-ci.org/pawaclawczyk/scalp.svg?branch=master)](https://travis-ci.org/pawaclawczyk/scalp)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/pawaclawczyk/scalp/badges/quality-score.png)](https://scrutinizer-ci.com/g/pawaclawczyk/scalp/)

Scalp
======

## Scalp
### Option
The `Option` type represents optional value. It can be either `Some` value or `None`.

```php
function divide(int $x, int $y): Option
{
    return $y === 0 ? None() : Some(intdiv($x, $y));
}

println(divide(42, 6));
println(divide(42, 0));
```

```bash
Some[int](7)
None
```

`Option` can be used as collection with `map`, `flatMap` or `filter`.

 ```php
$option = Option(42);

$square = function (int $x): int {
    return $x ** 2;
};

println($option->map($square));

$isOdd = function (int $x): bool {
    return $x % 2 === 1;
};

println($option->filter($isOdd));

$squareRoot = function (int $x): Option {
    return $x >= 0 ? Some(sqrt($x)) : None();
};

println($option->flatMap($squareRoot));
 ```

```bash
Some[integer](1764)
None
Some[double](6.4807406984079)
```

Computation performed on `Some` can also be performed on `None` without any side effect. The only difference is that
the result is always `None`.

```php
println(None()->map($square));
println(None()->filter($isOdd));
println(None()->flatMap($squareRoot));
```

```bash
None
None
None
```

### Partial Function Application
Partial function application lets to apply some of function arguments immediately, while rest of them can be applied later.

```php
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
```

```bash
Array(-2, 0, 2)
Array()
```

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

## Scalp\PatternMatching
```php
abstract class Notification implements CaseClass {};

final class Email extends Notification
{
    public function __construct(string $sender, string $title, string $body) { ... }
}

final class SMS extends Notification
{
    public function __construct(string $caller, string $message) { ... }
}

final class VoiceRecording extends Notification
{
    public function __construct(string $contactName, string $link) { ... }
}

function showNotification(Notification $notification): string
{
    return match($notification)
        ->case(
            Type(Email::class, Type('string')->bind(), Type('string')->bind(), Any()),
            papply(concat, 'You got an email from ', __, 'with title: ', __)
        )
        ->case(
            Type(SMS::class, Type('string')->bind(), Type('string')->bind()),
            papply(concat, 'You got an SMS from ', __, '! Message: ', __)
        )
        ->case(
            Type(VoiceRecording::class, Type('string')->bind(), Type('string')->bind()),
            papply(concat, 'You received a Voice Recording from ', __, '! Click the link to hear it: ', __)
        )
        ->done();
}

$someSms = new SMS('12345', 'Are you there?');
$someVoiceRecording = new VoiceRecording('Tom', 'voicerecording.org/id/123');

println(showNotification($someSms));
println(showNotification($someVoiceRecording));
```

```
You got an SMS from 12345! Message: Are you there?
You received a Voice Recording from Tom! Click the link to hear it: voicerecording.org/id/123
```

## Scalp\Utils
### Delayed
The `Delayed` type represents represents a postponed computation. It is created from a callable -- the computation and
its run arguments. The `Delayed` type is callable type, when called it executes the postponed computation.
In order to create delayed computation use factory method `dalay(callable $f, ...$args)`.

```php
use function Scalp\Utils\delay;

$delayed = delay(function (int $x): int { return $x * $x; }, 2);

echo $delayed();
```

```bash
4
```

### TryCatch
The `TryCatch` type represents computation that may either result in an exception, or return successful value.

```php
use function Scalp\Utils\delay;
use function Scalp\Utils\TryCatch;

$computation = function (int $divisor): int {
    return intdiv(42, $divisor);
};

$success = TryCatch(delay($computation, 7));
$failure = TryCatch(delay($computation, 0));

echo "Success: $success\n";
echo "Failure: $failure\n";
```

```bash
Success: Success[integer](6)
Failure: Failure[DivisionByZeroError]("Division by zero")
```
