<?php
declare(strict_types=1);

namespace Api\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ServiziController
{
    public function getAll(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = [
            ['idServizio' => 1, 'servizio' => 'Pulizia'],
            ['idServizio' => 2, 'servizio' => 'Manutenzione'],
            ['idServizio' => 3, 'servizio' => 'Assistenza'],
        ];

        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json');
    }
}