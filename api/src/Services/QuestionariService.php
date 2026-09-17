<?php
declare(strict_types=1);

namespace Api\Services;

use Api\Dto\Questionario;
use Api\Dto\Domanda;
use Api\Dto\Risultato;
use Api\Repositories\QuestionariRepository;

final class QuestionariService
{
    public function __construct(private QuestionariRepository $repository) {}

    public function getAll(): array
    {
        $questionari =  $this->repository->getAll();
        
        return $questionari ? array_map(fn($q) => new Questionario(
            ID: $q['idQuest'],
            Nome: $q['Nome'],
            Descrizione: $q['Descrizione'],
            Tipo: $q['Tipo'],
            Domande_Max: $q['Domande_Max'],
            Domande: array_map(fn($d) => new Domanda(ID: $d['idDomanda'], Descrizione: $d['Domanda']), $this->repository->getDomandeByIdQuest((int)$q['idQuest'])),
            Risultati: array_map(fn($r) => new Risultato(ID: $r['idRisultato'], Descrizione: $r['Descrizione'], Fascia_Min: $r['Fascia_Min'], Fascia_Max: $r['Fascia_Max']), $this->repository->getRisultatiByIdQuest((int)$q['idQuest']))
        ), $questionari) : [];
    }

    public function getById(int $id): Questionario
    {
        $quest = $this->repository->getById($id);
         

        return $quest ? new Questionario(
            ID: $quest['idQuest'],
            Nome: $quest['Nome'],
            Descrizione: $quest['Descrizione'],
            Tipo: $quest['Tipo'],
            Domande_Max: $quest['Domande_Max'],
            Domande: array_map(fn($d) => new Domanda(ID: $d['idDomanda'], Descrizione: $d['Domanda']), $this->repository->getDomandeByIdQuest($id)),
            Risultati: array_map(fn($r) => new Risultato(ID: $r['idRisultato'], Descrizione: $r['Descrizione'], Fascia_Min: $r['Fascia_Min'], Fascia_Max: $r['Fascia_Max']), $this->repository->getRisultatiByIdQuest($id))
        ) : throw new \Exception("Questionario non trovato");
    }
}

?>