<?php
declare(strict_types=1);

namespace Api\Services;

use Api\Dto\Utente;
use Api\Repositories\AdminRepository;
 use Firebase\JWT\JWT;

final class AdminService
{
    public function __construct(private AdminRepository $repo,
        private string $jwtSecret,
        private string $jwtIssuer,
        private int $jwtTtl
    ) {}



    /** AMMINISTRATORI  */

public function loginAdmin(string $email, string $password): array
{
    
    $admin = $this->repo->loginAdmin($email, $password);

    if (!$admin) {
        throw new \RuntimeException('Credenziali amministratore non valide');
    }

    $now = time();

    $payload = [
        'iss' => $this->jwtIssuer,
        'iat' => $now,
        'exp' => $now + $this->jwtTtl,
        'sub' => $admin['id'],
        'email' => $admin['email'],
        'isAdmin' => true
    ];

    $jwt = JWT::encode($payload, $this->jwtSecret, 'HS256');

    return [
        'token' => $jwt,
        'admin' => $admin
    ];
}
}