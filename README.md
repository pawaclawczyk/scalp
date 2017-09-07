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

## 2. Try 
