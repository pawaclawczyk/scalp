<?php

declare(strict_types=1);

namespace Scalp\Utils;

final class Delayed
{
    private $functionOrCodeBlock;
    private $args;

    public function __construct(callable $functionOrCodeBlock, ...$args)
    {
        $this->functionOrCodeBlock = $functionOrCodeBlock;
        $this->args = $args;
    }

    public function __invoke()
    {
        return ($this->functionOrCodeBlock)(...$this->args);
    }
}
