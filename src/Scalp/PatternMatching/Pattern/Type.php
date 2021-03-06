<?php

declare(strict_types=1);

namespace Scalp\PatternMatching\Pattern;

use const Scalp\__;
use Scalp\PatternMatching\CaseClass;
use const Scalp\Utils\isInstanceOfType;
use const Scalp\Utils\Success;
use function Scalp\None;
use Scalp\Option;
use function Scalp\papply;
use function Scalp\Some;
use function Scalp\Utils\delay;
use const Scalp\Utils\Failure;
use Scalp\Utils\TryCatch;
use function Scalp\Utils\type;

final class Type extends Pattern implements Binding
{
    use Bind;

    private $type;
    private $patterns;

    public function __construct(string $type, Pattern ...$patterns)
    {
        $this->type = $type;
        $this->patterns = $patterns;
    }

    public function match($x): Option
    {
        $typeMatch = Some($x)
            ->filter(papply(isInstanceOfType, __, $this->type));

        if ($typeMatch->isEmpty() || empty($this->patterns)) {
            return $typeMatch->flatMap(function (): Option { return Some([]); });
        }

        /** @var TryCatch $caseClass */
        $caseClass = $typeMatch
                ->filter(papply(isInstanceOfType, __, CaseClass::class))
                ->fold(
                    delay(Failure, new \RuntimeException('Argument must be CaseClass')),
                    Success
                );

        return $caseClass
                ->map(function (CaseClass $cc): array { return $cc->deconstruct(); })
                ->map(papply(\Closure::fromCallable([$this, 'applyConstructorArgumentsPatterns']), type($x), __, $this->patterns))
                ->get();
    }

    /**
     * @param string    $type
     * @param array     $arguments
     * @param Pattern[] $patterns
     *
     * @return Option
     */
    private function applyConstructorArgumentsPatterns(string $type, array $arguments, array $patterns): Option
    {
        $argumentsNumber = \count($arguments);
        $patternsNumber = \count($patterns);

        if ($argumentsNumber !== $patternsNumber) {
            return None();
        }

        $currentPattern = 0;

        $values = [];

        foreach ($arguments as $argument) {
            $pattern = $patterns[$currentPattern];

            $res = $pattern->match($argument);

            if ($res->isEmpty()) {
                return None();
            }

            $value = $res->get();

            $values = array_merge($values, is_array($value) ? $value : [$value]);

            $currentPattern = $currentPattern + 1;
        }

        return Some($values);
    }
}
