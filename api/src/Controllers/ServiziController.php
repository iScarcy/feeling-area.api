<?php
declare(strict_types=1);

namespace Api\Controllers;

use Api\Services\ServiziService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ServiziController
{
    public function __construct(private ServiziService $service) {}

    public function getAll(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = $this->service->getAll();

        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getById(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = (int)$args['id'];
        $data = $this->service->getById($id);

        if (!$data) {
            $response->getBody()->write(json_encode(['error' => 'Servizio non trovato'], JSON_UNESCAPED_UNICODE));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function create(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $input = json_decode((string)$request->getBody(), true);
        $data = $this->service->create($input);

        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE));
        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
    }

    public function update(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = (int)$args['id'];
        $input = json_decode((string)$request->getBody(), true);
        $data = $this->service->update($id, $input);

        if (!$data) {
            $response->getBody()->write(json_encode(['error' => 'Servizio non trovato'], JSON_UNESCAPED_UNICODE));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json');
    }
}