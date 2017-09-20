<?php

declare(strict_types=1);

namespace Scalp;

final class PartialApplication
{
    private $f;
    private $arguments;

    public function __construct(callable $f, array $arguments)
    {
        $this->guardAgainstMissingArguments($f, $arguments);

        $this->f = $f;
        $this->arguments = $arguments;
    }

    public function __invoke()
    {
        $appliedArguments = $this->applyArguments(func_get_args());

        $this->guardAgainstMissingAppliedArguments($appliedArguments);

        return ($this->f)(...$appliedArguments);
    }

    private function applyArguments(array $arguments): array
    {
        $argsIterator = new \ArrayIterator($arguments);

        $replacePlaceholders = function ($arg) use ($argsIterator) {
            $replacement = $arg === __ && $argsIterator->valid()
                ? $argsIterator->current()
                : $arg;

            $argsIterator->next();

            return $replacement;
        };

        return array_map($replacePlaceholders, $this->arguments);
    }

    private function guardAgainstMissingArguments(callable $f, array $arguments): void
    {
        $required = $this->countRequiredArguments($f);

        if (\count($arguments) < $required) {
            throw new \BadFunctionCallException('Number of passed arguments is less than required arguments. Use `__` const to add placeholder or value to apply.');
        }
    }

    private function guardAgainstMissingAppliedArguments(array $appliedArguments): void
    {
        $placeholders = $this->placeholderArguments($appliedArguments);

        if ($placeholders !== []) {
            throw new \BadFunctionCallException(sprintf(
                'Partially applied function has %d missing argument%s at position: %s.',
                \count($placeholders),
                \count($placeholders) > 1 ? 's' : '',
                implode(', ', array_map(function (int $idx): int {
                    return $idx + 1;
                }, array_keys($placeholders)))
            ));
        }
    }

    private function countRequiredArguments(callable $f): int
    {
        $rf = $this->reflectionFunction($f);

        [$count, $required] = array_reduce($rf->getParameters(), function (array $carry, \ReflectionParameter $parameter): array {
            $count = $carry[0] + 1;
            $required = $parameter->isOptional() ? $carry[1] : $count;

            return [$count, $required];
        }, [0, 0]);

        return $required;
    }

    private function placeholderArguments(array $arguments): array
    {
        return array_filter($arguments, function ($arg): bool { return $arg === __; });
    }

    private function reflectionFunction(callable $f): \ReflectionFunctionAbstract
    {
        if (is_object($f)) {
            return (new \ReflectionObject($f))->getMethod('__invoke');
        }

        if (is_array($f) && is_string($f[0])) {
            return (new \ReflectionClass($f[0]))->getMethod($f[1]);
        }

        if (is_array($f) && is_object($f[0])) {
            return (new \ReflectionObject($f[0]))->getMethod($f[1]);
        }

        return new \ReflectionFunction($f);
    }
}
