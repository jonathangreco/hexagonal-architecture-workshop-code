<?php

namespace Meetup\Infrastructure\Storage\InMemory;

use Meetup\Domain\Model\Meetup;
use Meetup\Domain\Model\MeetupId;
use Meetup\Domain\Model\MeetupRepository;

class InMemoryMeetupRepository implements MeetupRepository
{
    private $meetups;

    /**
     * @param Meetup $meetup
     * @return void
     */
    public function add(Meetup $meetup)
    {
        $this->meetups[] = $meetup;
    }

    /**
     * @param MeetupId $meetupId
     * @return Meetup
     */
    public function byId(MeetupId $meetupId)
    {

    }

    /**
     * @param \DateTimeImmutable $now
     * @return Meetup[]
     */
    public function upcomingMeetups(\DateTimeImmutable $now)
    {
        return $this->meetups;
    }

    /**
     * @param \DateTimeImmutable $now
     * @return Meetup[]
     */
    public function pastMeetups(\DateTimeImmutable $now)
    {

    }
}
