<?php
declare(strict_types=1);

namespace Api\Repositories;

final class ServiziRepository
{
    public function getAll(): array
    {
        // Per ora dati fittizi
        return [
            ['idServizio' => 1, 'servizio' => 'Form'],
            ['idServizio' => 2, 'servizio' => 'Manutenzione'],
            ['idServizio' => 3, 'servizio' => 'Assistenza'],
        ];
    }
}