<?php
declare(strict_types=1);

namespace Api\Repositories;

use PDO;

final class PacchettiRepository
{
    
 public function __construct(private PDO $db) {}

    public function getAll(): array
    {
        $stmt = $this->db->query(
            
            "SELECT p.idPacchetto, p.Pacchetto, p.Costo, GROUP_CONCAT(ps.idServizio) AS serviziInclusiIds
             FROM Pacchetti p
             LEFT JOIN Pacchetti_Servizi ps ON p.idPacchetto = ps.idPacchetto
             GROUP BY p.idPacchetto, p.Pacchetto, p.Costo"
        );

        return $stmt->fetchAll();
    }
    /*
    public function getById(int $id): ?array
    {
        foreach ($this->pacchetti as $pacchetto) {
            if ($pacchetto['ID'] === $id) {
                return $pacchetto;
            }
        }
        return null; // Pacchetto non trovato
    }

    public function addServizio(int $pacchettoId, int $servizioId): ?array
    {
        foreach ($this->pacchetti as &$pacchetto) {
            if ($pacchetto['ID'] === $pacchettoId) {
                if (!in_array($servizioId, $pacchetto['Servizi'])) {
                    $pacchetto['Servizi'][] = $servizioId;
                }
                return $pacchetto;
            }
        }
        return null; // Pacchetto non trovato
    }

    public function removeServizio(int $pacchettoId, int $servizioId): ?array
    {
        foreach ($this->pacchetti as &$pacchetto) {
            if ($pacchetto['ID'] === $pacchettoId) {
                $index = array_search($servizioId, $pacchetto['Servizi']);
                if ($index !== false) {
                    unset($pacchetto['Servizi'][$index]);
                    $pacchetto['Servizi'] = array_values($pacchetto['Servizi']); // Riorganizza gli indici
                }
                return $pacchetto;
            }
        }
        return null; // Pacchetto non trovato
    }
    */
   
}