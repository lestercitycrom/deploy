<?php
require __DIR__ . '/vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Exception\HttpNotFoundException;
use Slim\Middleware\ErrorMiddleware;
use App\Controllers\CustomersController;
use App\Controllers\OrdersController;
use App\Controllers\UsersController;

// Настройка Eloquent ORM
require __DIR__ . '/database.php';

$app = AppFactory::create();

$app->group('/api', function (\Slim\Routing\RouteCollectorProxy $group) {
    $group->get('/', function (Request $request, Response $response, $args) {
        $data = [
            'message' => 'Welcome to the API',
            'asd' => 'Welcome to the API_23'
        ];
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $group->get('/ping', function (Request $request, Response $response, $args) {
        $response->getBody()->write(json_encode(['message' => 'pong']));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // Маршруты с отдельными контроллерами
    $group->get('/customers', CustomersController::class);
    $group->get('/orders', OrdersController::class);
    $group->get('/users', UsersController::class);
});

// Middleware для обработки ошибок
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// Пользовательский обработчик ошибок 404
$errorMiddleware->setErrorHandler(HttpNotFoundException::class, function (
    Request $request,
    Throwable $exception,
    bool $displayErrorDetails,
    bool $logErrors,
    bool $logErrorDetails,
    ?Response $response = null
) use ($app) {
    $response = $app->getResponseFactory()->createResponse();
    $response->getBody()->write(json_encode(['error' => 'Route not found']));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
});

$app->run();
