<?php
declare(strict_types=1);

namespace Api\Dto;

use DateTime;

final class Pacchetto implements \JsonSerializable
{
    public function __construct(
        public int $ID,
        public string $Nome,
        public DateTime $DataCreazione,
        public DateTime $DataCancellazione,
        public float $Costo,
        public array $Servizi
    ) {}

    public function jsonSerialize(): array
    {
         
        return [
            'ID' => $this->ID,
            'Nome' => $this->Nome,
            'DataCreazione' => $this->DataCreazione->format(DateTime::ATOM),
            'DataCancellazione' => $this->DataCancellazione->format(DateTime::ATOM),
            'Costo' => $this->Costo,
            'Servizi' => $this->Servizi
        ];
    }

    public function withPassword(string $password): self
    {
        return new self(
            $this->ID, $this->Nome, $this->DataCreazione, $this->DataCancellazione,
            $this->Costo, $this->Servizi
        );
    }
}