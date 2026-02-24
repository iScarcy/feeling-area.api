<?php
declare(strict_types=1);

namespace Api\Controllers;

use Api\Services\UtentiService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class UtentiController
{
    public function __construct(private UtentiService $service) {}

    // CRUD

    public function list(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->json($response, $this->service->list());
    }

    public function get(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        try {
            $id = (int)($args['id'] ?? 0);
            return $this->json($response, $this->service->get($id));
        } catch (\Throwable $e) {
            return $this->json($response->withStatus(404), ['error' => $e->getMessage()]);
        }
    }

    public function create(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            $input = (array)($request->getParsedBody() ?? []);
            $created = $this->service->create($input);
            return $this->json($response->withStatus(201), $created);
        } catch (\Throwable $e) {
            return $this->json($response->withStatus(400), ['error' => $e->getMessage()]);
        }
    }

    public function update(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        try {
            $id = (int)($args['id'] ?? 0);
            $input = (array)($request->getParsedBody() ?? []);
            $updated = $this->service->update($id, $input);
            return $this->json($response, $updated);
        } catch (\Throwable $e) {
            return $this->json($response->withStatus(400), ['error' => $e->getMessage()]);
        }
    }

    public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        try {
            $id = (int)($args['id'] ?? 0);
            $this->service->delete($id);
            return $this->json($response, ['ok' => true]);
        } catch (\Throwable $e) {
            return $this->json($response->withStatus(404), ['error' => $e->getMessage()]);
        }
    }

    // Auth

    public function register(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            $input = (array)($request->getParsedBody() ?? []);
            $utente = $this->service->register($input);
            return $this->json($response->withStatus(201), $utente);
        } catch (\Throwable $e) {
            return $this->json($response->withStatus(400), ['error' => $e->getMessage()]);
        }
    }

    public function login(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            $input = (array)($request->getParsedBody() ?? []);
            $email = (string)($input['Email'] ?? '');
            $password = (string)($input['Password'] ?? '');

            if ($email === '' || $password === '') {
                return $this->json($response->withStatus(422), ['error' => 'Email e Password obbligatorie']);
            }

            $result = $this->service->login($email, $password);
            return $this->json($response, $result);
        } catch (\Throwable $e) {
            return $this->json($response->withStatus(401), ['error' => $e->getMessage()]);
        }
    }

    public function recoverPassword(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $input = (array)($request->getParsedBody() ?? []);
        $email = (string)($input['Email'] ?? '');

        if ($email === '') {
            return $this->json($response->withStatus(422), ['error' => 'Email obbligatoria']);
        }

        return $this->json($response, $this->service->recoverPassword($email));
    }

    private function json(ResponseInterface $response, mixed $data): ResponseInterface
    {
        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json');
    }
}