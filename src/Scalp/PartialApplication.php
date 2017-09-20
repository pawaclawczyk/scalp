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

        return ($this->f)(...$appliedArguments);
    }

    private function applyArguments(array $arguments): array
    {
        $argsIterator = new \ArrayIterator($arguments);

        $replacePlaceholders = function ($arg) use ($argsIterator) {
            $replacement = $arg === __ ? $argsIterator->current() : $arg;

            $argsIterator->next();

            return $replacement;
        };

        return array_map($replacePlaceholders, $this->arguments);
    }
}
