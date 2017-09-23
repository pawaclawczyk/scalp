<?php

declare(strict_types=1);

namespace Scalp\Collection;

use const Scalp\__;
use Scalp\Exception\NoSuchElementException;
use const Scalp\identity;
use const Scalp\inc;
use function Scalp\None;
use Scalp\Option;
use function Scalp\papply;
use Scalp\PatternMatching\CaseClass;
use Scalp\PatternMatching\Deconstruction;
use function Scalp\Some;
use const Scalp\throwE;
use function Scalp\Utils\delayed;

final class Tuple implements CaseClass
{
    use Deconstruction;

    private $elements;

    public function __construct(...$elements)
    {
        $this->construct(...$elements);

        $this->elements = $elements;
    }

    public function __get($name)
    {
        return $this
            ->elementId($name)
            ->map(papply(inc, __, -1))
            ->flatMap(\Closure::fromCallable([$this, 'element']))
            ->fold(
                delayed(throwE, NoSuchElementException::class, "Tuple->$name"),
                identity
            );
    }

    private function elementId(string $propertyName): Option
    {
        preg_match('/^_(\d+)$/', $propertyName, $matches);

        return isset($matches[1]) ? Some((int) $matches[1]) : None();
    }

    private function element(int $id): Option
    {
        return isset($this->elements[$id]) ? Some($this->elements[$id]) : None();
    }
}
