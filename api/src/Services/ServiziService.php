<?php
declare(strict_types=1);

namespace Api\Services;

use Api\Repositories\ServiziRepository;

final class ServiziService
{
    public function __construct(private ServiziRepository $repository) {}

    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    public function getById(int $id): ?array
    {
        return $this->repository->getById($id);
    }

    public function create(array $servizio): array
    {
        return $this->repository->create($servizio);
    }   

    public function update(int $id, array $servizio): ?array
    {
        return $this->repository->update($id, $servizio);
    }
}