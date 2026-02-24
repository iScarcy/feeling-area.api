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
}