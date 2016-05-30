<?php

namespace Meetup\Infrastructure\Web;

use Meetup\Application\ScheduleMeetup;
use Meetup\Application\ScheduleMeetupHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class ScheduleMeetupController
{
    /**
     * @var TemplateRendererInterface
     */
    private $renderer;
    
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var ScheduleMeetupHandler
     */
    private $scheduleMeetupHandler;

    public function __construct(TemplateRendererInterface $renderer, RouterInterface $router, ScheduleMeetupHandler $scheduleMeetupHandler)
    {
        $this->renderer = $renderer;
        $this->router = $router;
        $this->scheduleMeetupHandler = $scheduleMeetupHandler;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        if ($request->getMethod() === 'POST') {
            $submittedData = $request->getParsedBody();

            $command = new ScheduleMeetup();
            $command->name = $submittedData['name'];
            $command->description = $submittedData['description'];
            $command->scheduledFor = $submittedData['scheduledFor'];

            $this->scheduleMeetupHandler->handle($command);

            return new RedirectResponse($this->router->generateUri('list_meetups'));
        } else {
            $submittedData = [];
        }

        $response->getBody()->write(
            $this->renderer->render(
                'schedule-meetup.html.twig',
                ['submittedData' => $submittedData]
            )
        );

        return $response;
    }
}
