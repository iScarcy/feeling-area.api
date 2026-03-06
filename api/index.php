<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Api\Controllers\PacchettiController;
use Api\Controllers\ServiziController;
use Api\Controllers\UtentiController;
use Api\Middleware\JwtMiddleware;
use Dotenv\Dotenv;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/vendor/autoload.php';

// Load environment variables
// Carica .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Build container
$builder = new ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/config/container.php');
$container = $builder->build();

AppFactory::setContainer($container);

$jwtMiddleware = $container->get(JwtMiddleware::class);

$app = AppFactory::create();
$app->setBasePath('/api');

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(false, false, false);
$app->add(function (Request $request, $handler): Response {
    $response = $handler->handle($request);

    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

// gestisci le richieste OPTIONS (preflight)
$app->options('/{routes:.+}', function (Request $request, Response $response) {
    return $response;
});

// Routes
// Servizi


// CRUD protetto
$app->group('/utenti', function ($group) {
    $group->get('', [UtentiController::class, 'list']);
    $group->get('/{id}', [UtentiController::class, 'get']);
    $group->post('', [UtentiController::class, 'create']);
    $group->put('/{id}', [UtentiController::class, 'update']);
    $group->delete('/{id}', [UtentiController::class, 'delete']);
})->add($jwtMiddleware);

$app->group('/servizi', function ($group) {
    $group->get('', [ServiziController::class, 'getAll']);
    $group->get('/{id}', [ServiziController::class, 'getById']);
    $group->post('', [ServiziController::class, 'create']);
    $group->put('/{id}', [ServiziController::class, 'update']);
})->add($jwtMiddleware);

$app->group('/pacchetti', function ($group) {
    $group->get('', [PacchettiController::class, 'getAll']);
    $group->get('/{id}', [PacchettiController::class, 'getById']);
    $group->post('/{id}/add-servizio', [PacchettiController::class, 'addServizio']);
    $group->post('/{id}/remove-servizio', [PacchettiController::class, 'removeServizio']);
})->add($jwtMiddleware);

// Auth NON protetto
$app->post('/auth/register', [UtentiController::class, 'register']);
$app->post('/auth/login', [UtentiController::class, 'login']);
$app->post('/auth/recover-password', [UtentiController::class, 'recoverPassword']);

$app->run();