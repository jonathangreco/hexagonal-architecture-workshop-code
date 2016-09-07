<?php

use Interop\Container\ContainerInterface;
use Meetup\Application\ScheduleMeetupHandler;
use Meetup\Infrastructure\Persistence\FileBased\MeetupRepository;
use Meetup\Infrastructure\Web\ZendExpressive\Controller\ListMeetupsController;
use Meetup\Infrastructure\Web\ZendExpressive\Controller\ScheduleMeetupController;
use Meetup\Infrastructure\Web\ZendExpressive\Controller\Resources\Views\TwigTemplates;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Debug\Debug;
use Symfony\Component\Debug\ErrorHandler;
use Xtreamwayz\Pimple\Container;
use Zend\Expressive\Application;
use Zend\Expressive\Container\ApplicationFactory;
use Zend\Expressive\Helper\ServerUrlHelper;
use Zend\Expressive\Helper\UrlHelper;
use Zend\Expressive\Router\FastRouteRouter;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Expressive\Twig\TwigRendererFactory;

Debug::enable();
ErrorHandler::register();

$container = new Container();

$container['config'] = [
    'debug' => true,
    'templates' => [
        'extension' => 'html.twig',
        'paths' => [
            TwigTemplates::getPath()
        ]
    ],
    'twig' => [
        'extensions' => [
        ]
    ],
    'routes' => [
        [
            'name' => 'list_meetups',
            'path' => '/',
            'middleware' => ListMeetupsController::class,
            'allowed_methods' => ['GET']
        ],
        [
            'name' => 'schedule_meetup',
            'path' => '/schedule-meetup',
            'middleware' => ScheduleMeetupController::class,
            'allowed_methods' => ['GET', 'POST']
        ]
    ]
];

/*
 * Application
 */
$container['Zend\Expressive\FinalHandler'] = function () {
    return function (RequestInterface $request, ResponseInterface $response, $err = null) {
        if ($err instanceof \Exception) {
            throw $err;
        }
    };
};
$container[RouterInterface::class] = function () {
    return new FastRouteRouter();
};
$container[Application::class] = new ApplicationFactory();

/*
 * Templating
 */
$container[TemplateRendererInterface::class] = new TwigRendererFactory();
$container[ServerUrlHelper::class] = function () {
    return new ServerUrlHelper();
};
$container[UrlHelper::class] = function (ContainerInterface $container) {
    return new UrlHelper($container[RouterInterface::class]);
};

/*
 * Persistence
 */
$container[MeetupRepository::class] = function () {
    return new MeetupRepository(__DIR__ . '/../var/meetups.txt');
};

/*
 * Controllers
 */
$container[ScheduleMeetupController::class] = function (ContainerInterface $container) {
    return new ScheduleMeetupController(
        $container->get(TemplateRendererInterface::class),
        $container->get(RouterInterface::class),
        $container->get(ScheduleMeetupHandler::class)
    );
};
$container[ListMeetupsController::class] = function (ContainerInterface $container) {
    return new ListMeetupsController(
        $container->get(MeetupRepository::class),
        $container->get(TemplateRendererInterface::class),
        $container->get(RouterInterface::class)
    );
};

/**
 * CLI
 */
$container[\Meetup\Infrastructure\Cli\WebmozartConsole\Command\ScheduleMeetupConsoleHandler::class] = function (ContainerInterface $container) {
    return new \Meetup\Infrastructure\Cli\WebmozartConsole\Command\ScheduleMeetupConsoleHandler(
        $container->get(ScheduleMeetupHandler::class)
    );
};

/**
 * Application use cases
 */
$container[ScheduleMeetupHandler::class] = function (ContainerInterface $container) {
    return new ScheduleMeetupHandler(
        $container->get(MeetupRepository::class)
    );
};

return $container;
