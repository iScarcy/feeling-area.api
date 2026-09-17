<?php
declare(strict_types=1);

namespace Api\Dto;

final class Utente 
{
 
    public function __construct(
        public int $ID,
        public string $Nome,
        public string $Cognome,
        public string $Email,
        public string $Password,
        public string $DataNascita,
        public ?string $LuogoNascita,
        public ?string $Sesso,
        public ?string $Cellulare
    ) {}

    
}