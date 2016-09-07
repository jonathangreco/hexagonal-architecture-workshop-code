<?php

namespace Meetup\Infrastructure\Web\ZendExpressive\Controller;

use Meetup\Application\ScheduleMeetupHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
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
    private $handler;

    public function __construct(
        TemplateRendererInterface $renderer,
        RouterInterface $router,
        ScheduleMeetupHandler $handler
    ) {
        $this->renderer = $renderer;
        $this->router = $router;
        $this->handler = $handler;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        if ($request->getMethod() === 'POST') {
            $submittedData = $request->getParsedBody();

            $this->handler->__invoke(
                (string)Uuid::uuid4(),
                $submittedData['name'],
                $submittedData['description'],
                $submittedData['scheduledFor']
            );

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
