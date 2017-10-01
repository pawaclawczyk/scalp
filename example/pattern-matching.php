<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use Scalp\PatternMatching\CaseClass;
use Scalp\PatternMatching\Deconstruction;
use function Scalp\PatternMatching\match;
use function Scalp\PatternMatching\Type;
use function Scalp\PatternMatching\Any;
use function Scalp\println;
use function Scalp\papply;
use const Scalp\__;
use const Scalp\concat;

abstract class Notification implements CaseClass
{
}

final class Email extends Notification
{
    use Deconstruction;

    private $sender;
    private $title;
    private $body;

    public function __construct(string $sender, string $title, string $body)
    {
        $this->construct($sender, $title, $body);

        $this->sender = $sender;
        $this->title = $title;
        $this->body = $body;
    }
}

final class SMS extends Notification
{
    use Deconstruction;

    private $caller;
    private $message;

    public function __construct(string $caller, string $message)
    {
        $this->construct($caller, $message);

        $this->caller = $caller;
        $this->message = $message;
    }
}

final class VoiceRecording extends Notification
{
    use Deconstruction;

    private $contactName;
    private $link;

    public function __construct(string $contactName, string $link)
    {
        $this->construct($contactName, $link);

        $this->contactName = $contactName;
        $this->link = $link;
    }
}

function showNotification(Notification $notification): string
{
    return match($notification)
        ->case(
            Type(Email::class, Type('string')->bind(), Type('string')->bind(), Any()),
            papply(concat, 'You got an email from ', __, 'with title: ', __)
        )
        ->case(
            Type(SMS::class, Type('string')->bind(), Type('string')->bind()),
            papply(concat, 'You got an SMS from ', __, '! Message: ', __)
        )
        ->case(
            Type(VoiceRecording::class, Type('string')->bind(), Type('string')->bind()),
            papply(concat, 'You received a Voice Recording from ', __, '! Click the link to hear it: ', __)
        )
        ->done();
}

$someSms = new SMS('12345', 'Are you there?');
$someVoiceRecording = new VoiceRecording('Tom', 'voicerecording.org/id/123');

println(showNotification($someSms));
println(showNotification($someVoiceRecording));
