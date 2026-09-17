<?php
declare(strict_types=1);

namespace Api\Repositories;

use PDO;
final class AdminRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function loginAdmin(string $email, string $password): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT id, email, password
            FROM Amministratori
            WHERE email = :email
            LIMIT 1
        ");

        $stmt->execute([
            'email' => $email
        ]);

        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$admin) {
            return null;
        }

        if (!password_verify($password, $admin['password'])) {
            return null;
        }

        return [
            'id' => (int)$admin['id'],
            'email' => $admin['email'],
            'IsAdmin' => true
        ];
    }
}