<?php
declare(strict_types=1);

use Api\Controllers\ServiziController;
use Api\Services\ServiziService;
use Api\Repositories\ServiziRepository;

use Api\Controllers\UtentiController;
use Api\Services\UtentiService;
use Api\Repositories\UtentiRepository;

use Api\Middleware\JwtMiddleware;

use PDO;

$dbSettings = require __DIR__ . '/database.php';

return [

    // JWT
    'jwt.secret' => $_ENV['JWT_SECRET'],
    'jwt.issuer' => $_ENV['JWT_ISSUER'],
    'jwt.ttl' => (int)$_ENV['JWT_TTL'],

    /*
    |--------------------------------------------------------------------------
    | DATABASE (PDO)
    |--------------------------------------------------------------------------
    */

    PDO::class => function () use ($dbSettings) {

        $dsn = sprintf(
            "mysql:host=%s;dbname=%s;charset=%s",
            $dbSettings['host'],
            $dbSettings['dbname'],
            $dbSettings['charset']
        );

        return new PDO(
            $dsn,
            $dbSettings['user'],
            $dbSettings['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
    },

    /*
    |--------------------------------------------------------------------------
    | SERVIZI
    |--------------------------------------------------------------------------
    */

    ServiziRepository::class => DI\create()
        ->constructor(DI\get(PDO::class)),

    ServiziService::class => DI\create()
        ->constructor(DI\get(ServiziRepository::class)),

    ServiziController::class => DI\create()
        ->constructor(DI\get(ServiziService::class)),

    /*
    |--------------------------------------------------------------------------
    | UTENTI
    |--------------------------------------------------------------------------
    */

    UtentiRepository::class => DI\create(),

    UtentiService::class => DI\create()
        ->constructor(
            DI\get(UtentiRepository::class),
            DI\get('jwt.secret'),
            DI\get('jwt.issuer'),
            DI\get('jwt.ttl')
        ),

    UtentiController::class => DI\create()
        ->constructor(DI\get(UtentiService::class)),

    /*
    |--------------------------------------------------------------------------
    | MIDDLEWARE
    |--------------------------------------------------------------------------
    */

    JwtMiddleware::class => DI\create()
        ->constructor(DI\get('jwt.secret')),

];