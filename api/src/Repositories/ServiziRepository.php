<?php
declare(strict_types=1);

namespace Api\Repositories;

use PDO;

final class ServiziRepository
{
     
    public function __construct(private PDO $db) {}

    public function getAll(): array
    {
        $stmt = $this->db->query(
            "SELECT idServizio, Servizio FROM Servizi ORDER BY Servizio"
        );

        return $stmt->fetchAll();
    }

    /*
    public function getById(int $id): ?array
    {
        foreach ($this->servizi as $servizio) {
            if ($servizio['idServizio'] === $id) {
                return $servizio;
            }
        }
        return null; // Servizio non trovato
    }

    public function create(array $servizio): array
    {
        $newId = end($this->servizi)['idServizio'] + 1;
        $servizio['idServizio'] = $newId;
        $this->servizi[] = $servizio;
        return $servizio;
    }

    public function update(int $id, array $servizio): ?array
    {
        foreach ($this->servizi as &$s) {
            if ($s['idServizio'] === $id) {
                $s = array_merge($s, $servizio);
                return $s;
            }
        }
        return null;
    }
    */
}