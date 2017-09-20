<?php

declare(strict_types=1);

namespace Scalp;

final class PartialApplication
{
    private $f;
    private $arguments;

    public function __construct(callable $f, array $arguments)
    {
        $this->f = $f;
        $this->arguments = $arguments;
    }

    public function __invoke()
    {
        $appliedArguments = $this->applyArguments(func_get_args());

        $this->guardAgainstMissingArguments($appliedArguments);

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

    private function guardAgainstMissingArguments(array $appliedArguments): void
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

    private function placeholderArguments(array $arguments): array
    {
        return array_filter($arguments, function ($arg): bool { return $arg === __; });
    }
}
