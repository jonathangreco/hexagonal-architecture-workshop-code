<?php

namespace Meetup\Infrastructure\Cli;

use Meetup\Application\ScheduleMeetup;
use Meetup\Application\ScheduleMeetupHandler;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

class ScheduleMeetupConsoleHandler
{
    /**
     * @var ScheduleMeetupHandler
     */
    private $scheduleMeetupHandler;

    public function __construct(ScheduleMeetupHandler $scheduleMeetupHandler)
    {
        $this->scheduleMeetupHandler = $scheduleMeetupHandler;
    }

    public function handle(Args $args, IO $io)
    {

        $command = new ScheduleMeetup();
        $command->name = $args->getArgument('name');
        $command->description = $args->getArgument('description');
        $command->scheduledFor = $args->getArgument('scheduledFor');

        $this->scheduleMeetupHandler->handle($command);

        $io->writeLine('<success>Scheduled the meetup successfully</success>');
        
        return 0;
    }
}
