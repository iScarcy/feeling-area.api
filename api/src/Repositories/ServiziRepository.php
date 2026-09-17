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
            "SELECT idServizio, servizio FROM Servizi ORDER BY servizio"
        );

        return $stmt->fetchAll();
    }

    
    public function getByIdPacchetto(int $idPacchetto): ?array
    {
       $stmp = $this->db->prepare(
            "SELECT s.idServizio, s.servizio 
             FROM Servizi s
             JOIN Pacchetti_Servizi ps ON s.idServizio = ps.idServizio
             WHERE ps.idPacchetto = :idPacchetto"
        );
        $stmp->execute(['idPacchetto' => $idPacchetto]);
        return $stmp->fetchAll();
    }
    /*
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