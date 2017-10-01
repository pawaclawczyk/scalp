<?php

declare(strict_types=1);

namespace Scalp;

use const Scalp\__;
use Scalp\Exception\NoSuchElementException;
use const Scalp\identity;
use const Scalp\inc;
use function Scalp\None;
use function Scalp\papply;
use Scalp\PatternMatching\CaseClass;
use Scalp\PatternMatching\Deconstruction;
use function Scalp\Some;
use const Scalp\throwE;
use function Scalp\Utils\delay;

final class Tuple implements CaseClass
{
    use Deconstruction;

    private $elements;

    public function __construct(...$elements)
    {
        if (\count($elements) === 0) {
            throw new \InvalidArgumentException('Tuple must by construct with at least one element.');
        }

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
                delay(throwE, NoSuchElementException::class, "Tuple->$name"),
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
