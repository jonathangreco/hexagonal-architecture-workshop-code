<?php

namespace Meetup\Infrastructure\Cli\WebmozartConsole\Command;

use Meetup\Application\ScheduleMeetupHandler;
use Meetup\Domain\Model\Description;
use Meetup\Domain\Model\Meetup;
use Meetup\Domain\Model\MeetupId;
use Meetup\Domain\Model\Name;
use Ramsey\Uuid\Uuid;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

class ScheduleMeetupConsoleHandler
{
    /**
     * @var ScheduleMeetupHandler
     */
    private $handler;

    public function __construct(ScheduleMeetupHandler $handler)
    {
        $this->handler = $handler;
    }

    public function handle(Args $args, IO $io)
    {
        $this->handler->__invoke(
            (string) Uuid::uuid4(),
            $args->getArgument('name'),
            $args->getArgument('description'),
            $args->getArgument('scheduledFor')
        );

        $io->writeLine('<success>Scheduled the meetup successfully</success>');
        
        return 0;
    }
}
