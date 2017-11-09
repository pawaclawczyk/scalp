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

### Function Currying
Function currying allows to use function as a function of lower arity by providing arguments in separated steps.

```php
use function Scalp\curry;
use function Scalp\println;

$match = curry('preg_match');

$containsFoo = $match('/foo/');
$containsBar = $match('/bar/');

println($containsFoo('foobar'));   // 1
println($containsFoo('foofoo'));   // 1
println($containsFoo('barbar'));   // 0

println($containsBar('foobar'));   // 1
println($containsBar('foofoo'));   // 0
println($containsBar('barbar'));   // 1
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

### Tuple

`Tuple` is a data structure that holds elements of different types.
`Pair` is factory function for `Tuple` with two elements.

```php
$singleton = Tuple(42);

$pair      = Pair('Life', 42);

$triple    = Tuple('text', 27, false);
```

`Tuple` exposes its elements by properties with names `_1`, `_2` to `_N`.

_Elements of Tuple cannot be set._

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
Pattern matching is a mechanism for checking value against a pattern. You can think of it as of advanced switch statement.
In opposition to `switch` and `if` statements, pattern matching is an expression (it returns value, like ternary operator `?:`).
General use of pattern matching expression:

```php
$result = match($subject)
    ->case($pattern1, $callableToRunForPattern1)
    ->case($pattern2, $callableToRunForPattern2)
    ...
    ->case($patternN, $callableToRunForPatternN)
    ->done();
```

Case patterns are checked in order of declaration. When more general pattern is declared before specific one,
it will always fall into the general case.

### Case classes and type deconstruction
`CaseClass` is an interface that ensures existing of `deconstruct` method. `Deconstruct` method should return arguments
used to construct instance of given type. You can use trait `Deconstruction` to provide the ability to deconstruct.
It enables pattern matching to compare immutable complex type values.

In example the `Option` type is implemented as case class.

```php
abstract class Option implements CaseClass
{
    use Deconstruction;

    ...
}

final class Some extends Option
{
    private $value;

    public function __construct($value)
    {
        $this->construct($value);

        $this->value = $value;
    }

    ...
}

```

### Patterns
The most basic pattern is `Any`, it will match anything.
```php
$res0 = match(42)
    ->case(Any(), function (): string { return 'Anything'; })
    ->done();

// $res0 === 'Anything'

$res1 = match(Some(42))
    ->case(Any(), function (): string { return 'Anything'; })
    ->done();

// $res1 === 'Anything'
```

`Value` pattern does regular comparison with `===` for primitive types or `==` for objects.
When loose comparison is used, objects properties are also compared with `==` (see 3rd example).

```php
$res2 = match(42)
    ->case(Value(13), function (): string { return 'Number 13'; })
    ->case(Value('42'), function (): string { return 'String "42"'; })
    ->case(Value(42), function (): string { return 'Number 42'; })
    ->case(Any(), function (): string { return 'Fallback'; })
    ->done();

// $res2 === 'Number 42'

$res3 = match(Some(42))
    ->case(Value(Some(13)), function (): string { return 'Some 13'; })
    ->case(Value(Some(42)), function (): string { return 'Some 42'; })
    ->case(Any(), function (): string { return 'Fallback'; })
    ->done();

// $res3 === 'Some 42'

$res4 = match(Some(42))
    ->case(Value(Some('42')), function (): string { return 'Some 42'; })
    ->case(Any(), function (): string { return 'Fallback'; })
    ->done();

// $res4 === 'Some 42'
```

The `Type` can be used as simple pattern that checks value type.
```php
$res5 = match(42)
    ->case(Type('string'), function (): string { return 'String'; })
    ->case(Type('integer'), function (): string { return 'Integer'; })
    ->case(Any(), function (): string { return 'Not integer'; })
    ->done();

// $res5 === 'integer'

$res6 = match(Some(42))
    ->case(Type(None::class), function (): string { return 'None'; })
    ->case(Type(Some::class), function (): string { return 'Some'; })
    ->case(Any(), function (): string { return 'Neither'; })
    ->done();

// $res6 === 'Some'
```

`Type` pattern works with `CaseClass` deconstruction. It gives tha ability to look inside type construction
and pattern match its arguments.

```php
$res7 = match(Some(42))
    ->case(Type(Some::class, Value('42')), returnString('Inner value is string'))
    ->case(Type(Some::class, Value(42)), returnString('Inner value is integer'))
    ->case(Any(), returnString('Fallback'))
    ->done();

// $res7 === 'Inner value is integer'
```

### Value binding

Every value matched by a pattern can be bound and used as handler argument.

```php
$res8 = match(new Tuple('2 * 7 = ', 14))
    ->case(
        Type(Tuple::class, Any()->bind(), Any()->bind()),
        function (string $question, int $answer): string { return concat('Solution: ', $question, AnyToString($answer)); }
    )
    ->case(Any(), returnString('Fallback'))
    ->done();

// $res8 === 'Solution: 2 * 7 = 14'
```

### Example

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
The `Delayed` type represents a postponed computation. It is created from a callable -- the computation and
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
