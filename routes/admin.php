<?php

declare(strict_types=1);

use App\Core\Request;
use App\Core\Router;
use App\Controller\Admin\BlogController as AdminBlogController;
use App\Controller\Admin\DashboardController;
use App\Controller\Admin\MessageController;
use App\Controller\Admin\ProjectController as AdminProjectController;
use App\Controller\Admin\SettingsController;
use App\Controller\Admin\NewsletterController;
use App\Controller\AuthController;
use App\Middleware\AuthMiddleware;
use App\Middleware\CsrfMiddleware;
use App\Middleware\GuestMiddleware;

/** @var Router $router */

return static function (Router $router): void {
    $withMiddleware = require __DIR__ . '/middleware.php';

    /*
    |--------------------------------------------------------------------------
    | Guest Routes
    |--------------------------------------------------------------------------
    */

    // Registration
    $router->get('/admin/register', $withMiddleware(
        static function (Request $request, array $params = []): void {
            (new AuthController())->showRegister($request);
        },
        [new GuestMiddleware()]
    ));

    $router->post('/admin/register', $withMiddleware(
        static function (Request $request, array $params = []): void {
            (new AuthController())->register($request);
        },
        [new GuestMiddleware(), new CsrfMiddleware()]
    ));

    // Login
    $router->get('/admin/login', $withMiddleware(
        static function (Request $request, array $params = []): void {
            (new AuthController())->showLogin($request);
        },
        [new GuestMiddleware()]
    ));

    $router->post('/admin/login', $withMiddleware(
        static function (Request $request, array $params = []): void {
            (new AuthController())->login($request);
        },
        [new GuestMiddleware(), new CsrfMiddleware()]
    ));

    /*
    |--------------------------------------------------------------------------
    | Authenticated Admin Middleware
    |--------------------------------------------------------------------------
    */

    $auth = [
        new AuthMiddleware(),
    ];

    $authAndCsrf = [
        new AuthMiddleware(),
        new CsrfMiddleware(),
    ];

    // Logout
    $router->post('/admin/logout', $withMiddleware(
        static function (Request $request, array $params = []): void {
            (new AuthController())->logout($request);
        },
        $authAndCsrf
    ));

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    $router->get('/admin', $withMiddleware(
        static function (Request $request, array $params = []): void {
            (new DashboardController())->index($request);
        },
        $auth
    ));

    $router->get('/admin/dashboard', $withMiddleware(
        static function (Request $request, array $params = []): void {
            (new DashboardController())->index($request);
        },
        $auth
    ));

    /*
    |--------------------------------------------------------------------------
    | Project Management
    |--------------------------------------------------------------------------
    */

    $router->get('/admin/projects', $withMiddleware(
        static function (Request $request, array $params = []): void {
            (new AdminProjectController())->index($request);
        },
        $auth
    ));

    $router->get('/admin/projects/create', $withMiddleware(
        static function (Request $request, array $params = []): void {
            (new AdminProjectController())->create($request);
        },
        $auth
    ));

    $router->post('/admin/projects', $withMiddleware(
        static function (Request $request, array $params = []): void {
            (new AdminProjectController())->store($request);
        },
        $authAndCsrf
    ));

    $router->get('/admin/projects/{id}/edit', $withMiddleware(
        static function (Request $request, array $params = []): void {
            (new AdminProjectController())->edit(
                $request,
                (int) $params['id']
            );
        },
        $auth
    ));

    $router->put('/admin/projects/{id}', $withMiddleware(
        static function (Request $request, array $params = []): void {
            (new AdminProjectController())->update(
                $request,
                (int) $params['id']
            );
        },
        $authAndCsrf
    ));

    $router->delete('/admin/projects/{id}', $withMiddleware(
        static function (Request $request, array $params = []): void {
            (new AdminProjectController())->delete(
                $request,
                (int) $params['id']
            );
        },
        $authAndCsrf
    ));

    // Upload project thumbnail
    $router->post('/admin/projects/{id}/thumbnail', $withMiddleware(
        static function (Request $request, array $params = []): void {
            (new AdminProjectController())->uploadThumbnail(
                $request,
                $params
            );
        },
        $authAndCsrf
    ));

    /*
    |--------------------------------------------------------------------------
    | Blog Management
    |--------------------------------------------------------------------------
    */

    $router->get('/admin/blogs', $withMiddleware(
        static function (Request $request, array $params = []): void {
            (new AdminBlogController())->index($request);
        },
        $auth
    ));

    $router->get('/admin/blogs/create', $withMiddleware(
        static function (Request $request, array $params = []): void {
            (new AdminBlogController())->create($request);
        },
        $auth
    ));

    // Create a blog article, optionally including its featured image.
    $router->post('/admin/blogs', $withMiddleware(
        static function (Request $request, array $params = []): void {
            (new AdminBlogController())->store($request);
        },
        $authAndCsrf
    ));

    $router->get('/admin/blogs/{id}/edit', $withMiddleware(
        static function (Request $request, array $params = []): void {
            (new AdminBlogController())->edit(
                $request,
                (int) $params['id']
            );
        },
        $auth
    ));

    // Replace or upload a blog's featured image.
    $router->post('/admin/blogs/{id}/featured-image', $withMiddleware(
        static function (Request $request, array $params = []): void {
            (new AdminBlogController())->uploadFeaturedImage(
                $request,
                (int) $params['id']
            );
        },
        $authAndCsrf
    ));

    // Update blog text and other fields.
    $router->put('/admin/blogs/{id}', $withMiddleware(
        static function (Request $request, array $params = []): void {
            (new AdminBlogController())->update(
                $request,
                (int) $params['id']
            );
        },
        $authAndCsrf
    ));

    $router->delete('/admin/blogs/{id}', $withMiddleware(
        static function (Request $request, array $params = []): void {
            (new AdminBlogController())->destroy(
                $request,
                (int) $params['id']
            );
        },
        $authAndCsrf
    ));

    /*
|--------------------------------------------------------------------------
| Contact Messages
|--------------------------------------------------------------------------
*/

    // Display all contact messages.
    $router->get('/admin/messages', $withMiddleware(
        static function (Request $request, array $params = []): void {
            (new MessageController())->index($request);
        },
        $auth
    ));

    // Retrieve a single contact message.
    $router->get('/admin/messages/{id}', $withMiddleware(
        static function (Request $request, array $params = []): void {
            (new MessageController())->show(
                $request,
                (int) $params['id']
            );
        },
        $auth
    ));

    // Toggle message between read and unread.
    $router->patch('/admin/messages/{id}/status', $withMiddleware(
        static function (Request $request, array $params = []): void {
            (new MessageController())->toggleStatus(
                $request,
                (int) $params['id']
            );
        },
        $authAndCsrf
    ));

    // Send an email reply to the original sender.
    $router->post('/admin/messages/{id}/reply', $withMiddleware(
        static function (Request $request, array $params = []): void {
            (new MessageController())->sendReply(
                $request,
                (int) $params['id']
            );
        },
        $authAndCsrf
    ));

    // Delete a contact message.
    $router->delete('/admin/messages/{id}', $withMiddleware(
        static function (Request $request, array $params = []): void {
            (new MessageController())->destroy(
                $request,
                (int) $params['id']
            );
        },
        $authAndCsrf
    ));

    /*
    |--------------------------------------------------------------------------
    | Website Settings
    |--------------------------------------------------------------------------
    */

    $router->get('/admin/settings', $withMiddleware(
        static function (Request $request, array $params = []): void {
            (new SettingsController())->index($request);
        },
        $auth
    ));

    $router->post('/admin/settings', $withMiddleware(
        static function (Request $request, array $params = []): void {
            (new SettingsController())->update($request);
        },
        $authAndCsrf
    ));

    /*
    |--------------------------------------------------------------------------
    | Newsletter Management
    |--------------------------------------------------------------------------
    */

    // Display newsletter subscribers.
    $router->get('/admin/newsletter', $withMiddleware(
        static function (Request $request, array $params = []): void {
            (new NewsletterController())->index($request);
        },
        $auth
    ));

    // Send newsletter to active or selected subscribers.
    $router->post('/admin/newsletter/send', $withMiddleware(
        static function (Request $request, array $params = []): void {
            (new NewsletterController())->sendNewsletter($request);
        },
        $authAndCsrf
    ));

    // Update subscriber status.
    $router->put('/admin/newsletter/{id}/status', $withMiddleware(
        static function (Request $request, array $params = []): void {
            $id = filter_var(
                $params['id'] ?? null,
                FILTER_VALIDATE_INT,
                ['options' => ['min_range' => 1]]
            );

            if ($id === false) {
                http_response_code(400);
                header('Content-Type: application/json; charset=utf-8');

                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid subscriber ID.'
                ]);

                return;
            }

            (new NewsletterController())->updateStatus(
                $request,
                $id
            );
        },
        $authAndCsrf
    ));

    // Delete subscriber.
    $router->delete('/admin/newsletter/{id}', $withMiddleware(
        static function (Request $request, array $params = []): void {
            $id = filter_var(
                $params['id'] ?? null,
                FILTER_VALIDATE_INT,
                ['options' => ['min_range' => 1]]
            );

            if ($id === false) {
                http_response_code(400);
                header('Content-Type: application/json; charset=utf-8');

                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid subscriber ID.'
                ]);

                return;
            }

            (new NewsletterController())->destroy(
                $request,
                $id
            );
        },
        $authAndCsrf
    ));
};
