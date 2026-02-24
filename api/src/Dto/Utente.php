<?php
declare(strict_types=1);

namespace Api\Dto;

final class Utente implements \JsonSerializable
{
    public function __construct(
        public int $ID,
        public string $Nome,
        public string $Cognome,
        public string $Email,
        public string $Password,
        public string $Cell,
        public bool $IsAdmin
    ) {}

    public function jsonSerialize(): array
    {
        // Non esporre la password nelle risposte API
        return [
            'ID' => $this->ID,
            'Nome' => $this->Nome,
            'Cognome' => $this->Cognome,
            'Email' => $this->Email,
            'Cell' => $this->Cell,
            'IsAdmin' => $this->IsAdmin,
        ];
    }

    public function withPassword(string $password): self
    {
        return new self(
            $this->ID, $this->Nome, $this->Cognome, $this->Email,
            $password, $this->Cell, $this->IsAdmin
        );
    }
}