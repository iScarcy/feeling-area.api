<?php
declare(strict_types=1);

namespace Api\Repositories;

use Api\Dto\Utente;

final class UtentiRepository
{
    /**
     * Finto storage in-memory.
     * In produzione verrà sostituito con DB.
     */
    private static array $utenti = [];
    private static int $nextId = 1;

    public function __construct()
    {
        // Seed solo la prima volta
        if (empty(self::$utenti)) {
            $this->seed();
        }
    }

    /** @return Utente[] */
    public function getAll(): array
    {
        return array_values(self::$utenti);
    }

    public function findById(int $id): ?Utente
    {
        return self::$utenti[$id] ?? null;
    }

    public function findByEmail(string $email): ?Utente
    {
        foreach (self::$utenti as $u) {
            if (mb_strtolower($u->Email) === mb_strtolower($email)) return $u;
        }
        return null;
    }

    public function create(Utente $utente): Utente
    {
        $id = self::$nextId++;
        $created = new Utente(
            $id,
            $utente->Nome,
            $utente->Cognome,
            $utente->Email,
            $utente->Password,
            $utente->Cell,
            $utente->IsAdmin
        );
        self::$utenti[$id] = $created;
        return $created;
    }

    public function update(int $id, Utente $utente): ?Utente
    {
        if (!isset(self::$utenti[$id])) return null;

        $updated = new Utente(
            $id,
            $utente->Nome,
            $utente->Cognome,
            $utente->Email,
            $utente->Password,
            $utente->Cell,
            $utente->IsAdmin
        );
        self::$utenti[$id] = $updated;
        return $updated;
    }

    public function delete(int $id): bool
    {
        if (!isset(self::$utenti[$id])) return false;
        unset(self::$utenti[$id]);
        return true;
    }

    private function seed(): void
    {
        $admin = new Utente(
            0,
            'Admin',
            'User',
            'admin@example.com',
            password_hash('Admin123!', PASSWORD_DEFAULT),
            '0000000000',
            true
        );
        $this->create($admin);

        $user = new Utente(
            0,
            'Mario',
            'Rossi',
            'mario.rossi@example.com',
            password_hash('Password123!', PASSWORD_DEFAULT),
            '3331234567',
            false
        );
        $this->create($user);
    }
}