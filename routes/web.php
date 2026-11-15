<?php

declare(strict_types=1);

use App\Core\Request;
use App\Core\Router;
use App\Controller\BlogController;
use App\Controller\ContactController;
use App\Controller\HomeController;
use App\Controller\ProjectController;
use App\Controller\Admin\NewsletterController;
use App\Middleware\CsrfMiddleware;

/** @var Router $router */
return static function (Router $router): void {

    $withMiddleware = require __DIR__ . '/middleware.php';

    // HOME
    $router->get('/', static function (Request $request, array $params = []) {
        (new HomeController())->index($request);
    });

    // PROJECTS
    $router->get('/projects', static function (Request $request, array $params = []) {
        (new ProjectController())->index($request);
    });

    // BLOG LISTING
    $router->get('/blog', static function (Request $request, array $params = []) {
        (new BlogController())->index($request);
    });

    // SINGLE BLOG POST
    $router->get('/blog/{slug}', static function (Request $request, array $params = []) {
        (new BlogController())->show($request, $params['slug']);
    });

    // PUBLIC NEWSLETTER SUBSCRIPTION
    $router->post('/newsletter/subscribe', $withMiddleware(
        static function (Request $request, array $params = []): void {
            (new NewsletterController())->subscribe($request);
        },
        [new CsrfMiddleware()]
    ));

    // CONTACT PAGE
    $router->get('/contact', static function (Request $request, array $params = []) {
        (new ContactController())->index($request);
    });

    // CONTACT FORM SUBMISSION
    $router->post('/contact', $withMiddleware(
        static function (Request $request, array $params = []) {
            (new ContactController())->store($request);
        },
        [new CsrfMiddleware()]
    ));
};
