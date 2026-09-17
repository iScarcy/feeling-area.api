<?php
declare(strict_types=1);

namespace Api\Dto;

final class UtentePacchettiServizi 
{
 
    public function __construct(
        public int $ID,
        public string $Nome,
        public string $Cognome,
        public int $idPacchetto,
        public string $Pacchetto,
        public int $idServizio,
        public string $Servizio,
        public ?int $idQuestionario,
        public ?string $QuestionarioPunteggio
    ) {}

    
}