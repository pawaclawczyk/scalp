<?php

declare(strict_types=1);

namespace Scalp\PatternMatching\Exception;

final class InvalidPatternsNumber extends \RuntimeException
{
    public static function create(string $type, int $arguments, int $patterns): InvalidPatternsNumber
    {
        return new self(sprintf('Instance of type "%s" was created with %d arguments, but here is %d patterns provided.', $type, $arguments, $patterns));
    }
}
