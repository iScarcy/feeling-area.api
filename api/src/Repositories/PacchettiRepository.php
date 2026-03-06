<?php
declare(strict_types=1);

namespace Api\Repositories;

final class PacchettiRepository
{
    // Per ora dati fittizi, in futuro connettiti al database
    public array $pacchetti = [

        [
            'ID' => 1,
            'Nome' => 'Pacchetto Base',
            'DataCreazione' => null,
            'DataCancellazione' => null,
            'Costo' => 99.99,
            'Servizi' => [1] // ID dei servizi inclusi
        ],
    ];

    public function getAll(): array
    {
         return $this->pacchetti;
    }

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

   
}