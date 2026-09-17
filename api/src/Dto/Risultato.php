<?php
declare(strict_types=1);

namespace Api\Dto;

final class Risultato
{
    public function __construct(
        public int $ID,
        public string $Descrizione,
        public float $Fascia_Min,
        public float $Fascia_Max
    ) {}

   
}
?>