<?php

declare(strict_types=1);

namespace Scalp\PatternMatching;

use function Scalp\Conversion\AnyToString;

final class PatternMatchingSubjectNotFound extends \RuntimeException
{
    public static function for($x): PatternMatchingSubjectNotFound
    {
        return new self('Pattern matching subject "'.AnyToString($x).'" is not defined in this match expression".');
    }
}
