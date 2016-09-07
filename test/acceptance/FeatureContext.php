<?php

namespace Tests\Acceptance;

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Meetup\Application\ScheduleMeetupHandler;
use Meetup\Domain\Model\Meetup;
use Meetup\Infrastructure\Persistence\InMemory\InMemoryMeetupRepository;
use Ramsey\Uuid\Uuid;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
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
     * @When I schedule a :name with the description :description on :scheduledFor
     */
    public function iScheduleAWithTheDescriptionOn($name, $description, $scheduledFor)
    {
        $handler = new ScheduleMeetupHandler(
            $this->repository
        );

        $handler->__invoke(
            (string)Uuid::uuid4(),
            $name,
            $description,
            $scheduledFor
        );
    }

    /**
     * @Then there will be an upcoming meetup called :name scheduled for :scheduledFor
     */
    public function thereWillBeAnUpcomingMeetupCalledScheduledFor($name, $scheduledFor)
    {
        $upcomingMeetups = $this->repository->upcomingMeetups(new \DateTimeImmutable());

        foreach ($upcomingMeetups as $meetup) {
            /** @var $meetup Meetup */
            if ((string)$meetup->name() == $name && $meetup->scheduledFor() == new \DateTimeImmutable($scheduledFor)) {
                return;
            }
        }

        throw new \RuntimeException('Meetup not found');
    }
}
