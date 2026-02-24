<?php
declare(strict_types=1);

use Api\Controllers\ServiziController;
use Api\Services\ServiziService;
use Api\Repositories\ServiziRepository;
use Api\Controllers\UtentiController;
use Api\Services\UtentiService;
use Api\Repositories\UtentiRepository;


return [
    // Repository
    ServiziRepository::class => DI\create(ServiziRepository::class),

    // Service
    ServiziService::class => DI\create(ServiziService::class)
        ->constructor(DI\get(ServiziRepository::class)),

    // Controller
    ServiziController::class => DI\create(ServiziController::class)
        ->constructor(DI\get(ServiziService::class)),

    UtentiRepository::class => DI\create(UtentiRepository::class),
    UtentiService::class => DI\create(UtentiService::class)
        ->constructor(DI\get(UtentiRepository::class)),
    UtentiController::class => DI\create(UtentiController::class)
        ->constructor(DI\get(UtentiService::class)),    
];