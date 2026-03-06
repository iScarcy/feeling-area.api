<?php
declare(strict_types=1);

namespace Api\Dto;

use DateTime;

final class Servizio implements \JsonSerializable
{
    public function __construct(
        public int $ID,
        public string $Nome,
        public DateTime $DataCreazione,
        public DateTime $DataCancellazione
        
    ) {}

    public function jsonSerialize(): array
    {
        // Non esporre la password nelle risposte API
        return [
            'ID' => $this->ID,
            'Nome' => $this->Nome,
            'DataCreazione' => $this->DataCreazione->format(DateTime::ATOM),
            'DataCancellazione' => $this->DataCancellazione->format(DateTime::ATOM)
        ];
    }

    public function withPassword(string $password): self
    {
        return new self(
            $this->ID, $this->Nome, $this->DataCreazione, $this->DataCancellazione
        );
    }
}