<?php

declare(strict_types=1);

namespace Scalp\Collection;

use Scalp\Exception\NoSuchElementException;
use Scalp\PatternMatching\CaseClass;
use Scalp\PatternMatching\Deconstruction;

final class Tuple implements CaseClass
{
    use Deconstruction;

    private $elements;

    public function __construct(...$elements)
    {
        /*
         * @todo How to force this?
         */
        $this->construct(...$elements);

        $this->elements = $elements;
    }

    public function __get($name)
    {
        preg_match('/^__(\d+)$/', $name, $matches);

        if (!isset($matches[1])) {
            throw new NoSuchElementException("Tuple->$name");
        }

        $element = $matches[1] - 1;

        if (!isset($this->elements[$element])) {
            throw new NoSuchElementException("Tuple->$name");
        }

        return $this->elements[$element];
    }
}
