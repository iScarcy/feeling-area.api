<?php
declare(strict_types=1);

namespace Api\Dto;

final class Domanda
{
    public function __construct(
        public int $ID,
        public string $Descrizione
    ) {}

   
}
?>