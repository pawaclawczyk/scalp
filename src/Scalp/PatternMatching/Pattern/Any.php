<?php

declare(strict_types=1);

namespace Scalp\PatternMatching\Pattern;

use Scalp\Option;
use function Scalp\Some;

final class Any extends Pattern implements Binding
{
    use Bind;

    public function match($x): Option
    {
        return Some([]);
    }
}
