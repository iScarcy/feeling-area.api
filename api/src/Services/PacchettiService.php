<?php
declare(strict_types=1);

namespace Api\Services;

use Api\Repositories\PacchettiRepository;

final class PacchettiService
{
    public function __construct(private PacchettiRepository $repository) {}

    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    public function getById(int $id): ?array
    {
        return $this->repository->getById($id);
    }

    public function addServizio(int $pacchettoId, int $servizioId): ?array
    {
        return $this->repository->addServizio($pacchettoId, $servizioId);
    }

    public function removeServizio(int $pacchettoId, int $servizioId): ?array
    {
        return $this->repository->removeServizio($pacchettoId, $servizioId);
    }

    
}