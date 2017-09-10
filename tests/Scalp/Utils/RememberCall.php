<?php

declare(strict_types=1);

namespace Scalp\Tests\Utils;

final class RememberCall
{
    private $calledWith;

    public function __invoke($argument): void
    {
        $this->calledWith = $argument;
    }

    public function calledWith()
    {
        return $this->calledWith;
    }
}
