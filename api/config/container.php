<?php
declare(strict_types=1);

use Api\Controllers\ServiziController;
use Api\Services\ServiziService;
use Api\Repositories\ServiziRepository;

use Api\Controllers\UtentiController;
use Api\Services\UtentiService;
use Api\Repositories\UtentiRepository;

use Api\Controllers\QuestionariController;
use Api\Services\QuestionariService;  
use Api\Repositories\QuestionariRepository;

use Api\Controllers\AdminController;
use Api\Services\AdminService;
use Api\Repositories\AdminRepository;

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
    | QUESTIONARI
    |--------------------------------------------------------------------------
    */
    QuestionariRepository::class => DI\create()
        ->constructor(DI\get(PDO::class)),
     
    QuestionariService::class => DI\create()
        ->constructor(DI\get(QuestionariRepository::class)),
        
    QuestionariController::class => DI\create()
        ->constructor(DI\get(QuestionariService::class)),    

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

    UtentiRepository::class => DI\create()
         ->constructor(DI\get(PDO::class)),

    UtentiService::class => DI\create()
        ->constructor(
            DI\get(UtentiRepository::class), DI\get(QuestionariService::class),
            DI\get('jwt.secret'),
            DI\get('jwt.issuer'),
            DI\get('jwt.ttl')
        ),

    UtentiController::class => DI\create()
        ->constructor(DI\get(UtentiService::class)),

     /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */

    AdminRepository::class => DI\create()
         ->constructor(DI\get(PDO::class)),

    AdminService::class => DI\create()
        ->constructor(
            DI\get(AdminRepository::class),
            DI\get('jwt.secret'),
            DI\get('jwt.issuer'),
            DI\get('jwt.ttl')
        ),

    AdminController::class => DI\create()
        ->constructor(DI\get(AdminService::class)),

    /*
    |--------------------------------------------------------------------------
    | MIDDLEWARE
    |--------------------------------------------------------------------------
    */

    JwtMiddleware::class => DI\create()
        ->constructor(DI\get('jwt.secret')),

];