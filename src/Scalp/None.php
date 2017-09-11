<?php

declare(strict_types=1);

namespace Scalp;

use Scalp\Exception\NoSuchElementException;

final class None extends Option
{
    public function isEmpty(): bool
    {
        return true;
    }

    public function get(): void
    {
        throw new NoSuchElementException('None::get');
    }
}
