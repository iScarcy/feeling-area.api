<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Api\Controllers\ServiziController;
use Api\Controllers\UtentiController;
use Api\Middleware\JwtMiddleware;
use Dotenv\Dotenv;

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

// Routes
// Servizi
$app->get('/servizi', [ServiziController::class, 'getAll']);

// CRUD protetto
$app->group('/utenti', function ($group) {
    $group->get('', [UtentiController::class, 'list']);
    $group->get('/{id}', [UtentiController::class, 'get']);
    $group->post('', [UtentiController::class, 'create']);
    $group->put('/{id}', [UtentiController::class, 'update']);
    $group->delete('/{id}', [UtentiController::class, 'delete']);
})->add($jwtMiddleware);

// Auth NON protetto
$app->post('/auth/register', [UtentiController::class, 'register']);
$app->post('/auth/login', [UtentiController::class, 'login']);
$app->post('/auth/recover-password', [UtentiController::class, 'recoverPassword']);

$app->run();