<?php

namespace Meetup\Application;

use Meetup\Domain\Model\Description;
use Meetup\Domain\Model\Meetup;
use Meetup\Domain\Model\MeetupId;
use Meetup\Domain\Model\MeetupRepository;
use Meetup\Domain\Model\Name;
use Ramsey\Uuid\Uuid;

class ScheduleMeetupHandler
{
    /**
     * @var MeetupRepository
     */
    private $repository;

    public function __construct(MeetupRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(ScheduleMeetup $command)
    {
        $meetup = Meetup::schedule(
            MeetupId::fromString((string) Uuid::uuid4()),
            Name::fromString($command->name),
            Description::fromString($command->description),
            new \DateTimeImmutable($command->scheduledFor)
        );
        $this->repository->add($meetup);
    }
}
