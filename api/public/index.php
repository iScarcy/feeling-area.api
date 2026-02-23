<?php
declare(strict_types=1);

use Slim\Factory\AppFactory;
use Api\Controllers\ServiziController;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/servizi', [ServiziController::class, 'getAll']);

$app->run();