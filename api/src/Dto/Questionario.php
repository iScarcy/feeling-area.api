<?php
declare(strict_types=1);

namespace Api\Dto;

final class Questionario
{
    public function __construct(
        public int $ID,
        public string $Nome,
        public string $Descrizione,
        public int $NumeroRisposte,
        public array $Domande = [],
        public array $Risultati = []
    ) {}

   
}
?>