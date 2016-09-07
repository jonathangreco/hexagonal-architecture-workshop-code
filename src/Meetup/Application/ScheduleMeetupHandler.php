<?php

namespace Meetup\Application;

use Meetup\Domain\Model\Description;
use Meetup\Domain\Model\Meetup;
use Meetup\Domain\Model\MeetupId;
use Meetup\Domain\Model\Name;
use Meetup\Infrastructure\Persistence\FileBased\MeetupRepository;

final class ScheduleMeetupHandler
{
    /**
     * @var MeetupRepository
     */
    private $repository;

    public function __construct(MeetupRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke($id, $name, $description, $scheduledFor)
    {
        $meetup = Meetup::schedule(
            MeetupId::fromString($id),
            Name::fromString($name),
            Description::fromString($description),
            new \DateTimeImmutable($scheduledFor)
        );

        $this->repository->add($meetup);
    }
}
