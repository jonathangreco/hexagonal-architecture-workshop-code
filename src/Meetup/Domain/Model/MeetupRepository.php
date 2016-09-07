<?php
namespace Meetup\Domain\Model;

interface MeetupRepository
{
    public function add(Meetup $meetup);

    public function byId(MeetupId $meetupId);

    public function upcomingMeetups(\DateTimeImmutable $now);

    public function pastMeetups(\DateTimeImmutable $now);
}
