<?php

declare(strict_types=1);

namespace Scalp\Tests\PatternMatching;

use PHPUnit\Framework\TestCase;
use const Scalp\__;
use Scalp\Collection\Tuple;
use const Scalp\Conversion\AnyToString;
use const Scalp\identity;
use Scalp\None;
use function Scalp\papply;
use function Scalp\PatternMatching\match;
use function Scalp\PatternMatching\Type;
use function Scalp\Some;
use function Scalp\Utils\delayed;
use function Scalp\PatternMatching\Any;

final class PatternMatchingTest extends TestCase
{
    /** @test */
    public function it_does_simple_pattern_matching(): void
    {
        $concat = function (...$strings): string {
            return implode('', array_map(AnyToString, $strings));
        };

        $subject = new Tuple(Some('Life'), Some(42));

        $result = match($subject)
            ->case(
                Type(Tuple::class, Type(None::class), Type(None::class)),
                delayed(identity, 'No question, no answer.')
            )
            ->case(
                Type(Tuple::class, Any()->bind(), Any()->bind()),
                papply($concat, 'The question: ', __, '? And the answer: ', __)
            )
            ->case(
                Any(),
                delayed(identity, 'It does not work this way...')
            )
            ->done();

        $this->assertEquals('The question: Some[string](Life)? And the answer: Some[integer](42)', $result);
    }
}
