<?php

namespace Tests\Acceptance;

use Assert\Assertion;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Meetup\Application\ScheduleMeetup;
use Meetup\Application\ScheduleMeetupHandler;
use Meetup\Domain\Model\Meetup;
use Meetup\Infrastructure\Storage\InMemory\InMemoryMeetupRepository;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
    /**
     * @var InMemoryMeetupRepository
     */
    private $repository;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->repository = new InMemoryMeetupRepository();
    }

    /**
     * @When I schedule a :name with the description :description on :scheduleFor
     */
    public function iScheduleAWithTheDescriptionOn($name, $description, $scheduleFor)
    {
        $command = new ScheduleMeetup();
        $command->name = $name;
        $command->description = $description;
        $command->scheduledFor = $scheduleFor;

        $handler = new ScheduleMeetupHandler($this->repository);
        $handler->handle($command);
    }

    /**
     * @Then there will be an upcoming meetup called :name scheduled for :scheduledFor
     */
    public function thereWillBeAnUpcomingMeetupCalledScheduledFor($name, $scheduledFor)
    {
        $upcomingMeetups = $this->repository->upcomingMeetups(new \DateTimeImmutable());

        $meetup = reset($upcomingMeetups);
        /** @var $meetup Meetup */

        Assertion::eq($name, (string)$meetup->name());

        Assertion::eq(new \DateTimeImmutable($scheduledFor), $meetup->scheduledFor());
    }
}
