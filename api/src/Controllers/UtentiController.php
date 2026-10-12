<?php
declare(strict_types=1);

namespace Api\Controllers;

use Api\Services\UtentiService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class UtentiController
{
    public function __construct(private UtentiService $service) {}

    

    
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

    private function json(ResponseInterface $response, mixed $data): ResponseInterface
    {
        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json');
    }

   
}