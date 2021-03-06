<?php

namespace Meetup\Controller;

use Meetup\Model\MeetupRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Stratigility\MiddlewareInterface;

class ListMeetupsController implements MiddlewareInterface
{
    /**
     * @var MeetupRepository
     */
    private $meetupRepository;

    /**
     * @var TemplateRendererInterface
     */
    private $renderer;

    public function __construct(MeetupRepository $meetupRepository, TemplateRendererInterface $renderer)
    {
        $this->meetupRepository = $meetupRepository;
        $this->renderer = $renderer;
    }

    public function __invoke(Request $request, Response $response, callable $out = null)
    {
        $now = new \DateTimeImmutable();
        $upcomingMeetups = $this->meetupRepository->upcomingMeetups($now);
        $pastMeetups = $this->meetupRepository->pastMeetups($now);

        $response->getBody()->write($this->renderer->render('list-meetups.html.twig', [
            'upcomingMeetups' => $upcomingMeetups,
            'pastMeetups' => $pastMeetups
        ]));

        return $response;
    }
}
