<?php
declare(strict_types=1);

namespace Api\Controllers;

use Api\Services\AdminService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class AdminController
{
    public function __construct(private AdminService $service) {}

    private function json(ResponseInterface $response, mixed $data): ResponseInterface
    {
        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json');
    }
    
    // --- AMMINISTRATORI ---

     public function loginAdmin(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            $input = (array)($request->getParsedBody() ?? []);
            $email = (string)($input['Email'] ?? '');
            $password = (string)($input['Password'] ?? '');

            if ($email === '' || $password === '') {
                return $this->json($response->withStatus(422), ['error' => 'Email e Password obbligatorie']);
            }

            $result = $this->service->loginAdmin($email, $password);
            return $this->json($response, $result);
        } catch (\Throwable $e) {
            return $this->json($response->withStatus(401), ['error' => $e->getMessage()]);
        }
    }
}