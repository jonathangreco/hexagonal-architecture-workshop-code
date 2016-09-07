<?php

namespace Meetup\Infrastructure\Persistence\InMemory;

use Meetup\Domain\Model\Meetup;
use Meetup\Domain\Model\MeetupId;
use Meetup\Domain\Model\MeetupRepository;

final class InMemoryMeetupRepository implements MeetupRepository
{
    private $meetups = [];

    public function add(Meetup $meetup)
    {
        $this->meetups[(string)$meetup->id()] = $meetup;
    }

    public function byId(MeetupId $meetupId)
    {
        if (!isset($this->meetups[(string)$meetupId])) {
            throw new \RuntimeException();
        }

        return $this->meetups[(string)$meetupId];
    }

    public function upcomingMeetups(\DateTimeImmutable $now)
    {
        return $this->meetups;
    }

    public function pastMeetups(\DateTimeImmutable $now)
    {
        return $this->meetups;
    }
}
