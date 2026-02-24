<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Api\Controllers\ServiziController;
use Api\Controllers\UtentiController;

require __DIR__ . '/vendor/autoload.php';

// Build container
$builder = new ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/config/container.php');
$container = $builder->build();

AppFactory::setContainer($container);

$app = AppFactory::create();
$app->setBasePath('/api');

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(false, false, false);

// Routes
// Servizi
$app->get('/servizi', [ServiziController::class, 'getAll']);

// CRUD Utenti
$app->get('/utenti', [UtentiController::class, 'list']);
$app->get('/utenti/{id}', [UtentiController::class, 'get']);
$app->post('/utenti', [UtentiController::class, 'create']);
$app->put('/utenti/{id}', [UtentiController::class, 'update']);
$app->delete('/utenti/{id}', [UtentiController::class, 'delete']);

// Auth
$app->post('/auth/register', [UtentiController::class, 'register']);
$app->post('/auth/login', [UtentiController::class, 'login']);
$app->post('/auth/recover-password', [UtentiController::class, 'recoverPassword']);

$app->run();