<?php

declare(strict_types=1);

namespace Scalp\Tests\PatternMatching;

use Scalp\PatternMatching\CaseClass;
use Scalp\PatternMatching\Deconstruction;

final class Subject implements CaseClass
{
    use Deconstruction;

    public function __construct(...$arguments)
    {
        $this->construct(...$arguments);
    }
}
