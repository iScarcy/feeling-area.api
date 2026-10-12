<?php
declare(strict_types=1);

namespace Api\Repositories;

use PDO;

final class QuestionariRepository
{
    public function __construct(private PDO $db) {}

    public function getAll(): array
    {
        $stmt = $this->db->query(
            "SELECT idQuest, Nome, Descrizione, NumeroRisposte, Attivo FROM `Questionari` ORDER BY Nome"
        ); 

        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT idQuest, Nome, Descrizione, Attivo FROM `Questionari` WHERE idQuest = :id"
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getDomandeByIdQuest(int $idQuest): array
    {
        $stmt = $this->db->prepare(
            "SELECT idDomanda, Domanda FROM `Domande` WHERE idQuest = :idQuest"
        );
        $stmt->execute(['idQuest' => $idQuest]);
        return $stmt->fetchAll();
    }

    public function getRisultatiByIdQuest(int $idQuest): array
    {
        $stmt = $this->db->prepare(
            "SELECT r.idRisultato, r.Descrizione, r.Fascia_Min, r.Fascia_Max 
             FROM Risultati r
             WHERE r.idQuest = :idQuest"
        );
        $stmt->execute(['idQuest' => $idQuest]);
        return $stmt->fetchAll();
    }

    public function createDomanda(int $idQuest, string $domanda): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO `Domande` (idQuest, Domanda) VALUES (:idQuest, :domanda)"
        );
        $stmt->execute(['idQuest' => $idQuest, 'domanda' => $domanda]);
        return (int)$this->db->lastInsertId();
    }

    public function createRisultato(int $idQuest, string $descrizione, int $fasciaMin, int $fasciaMax): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO `Risultati` (idQuest, Descrizione, Fascia_Min, Fascia_Max) VALUES (:idQuest, :descrizione, :fasciaMin, :fasciaMax)"
        );
        $stmt->execute([
            'idQuest' => $idQuest,
            'descrizione' => $descrizione,
            'fasciaMin' => $fasciaMin,
            'fasciaMax' => $fasciaMax
        ]);
        return (int)$this->db->lastInsertId();
    }   

    public function createQuestionario(string $nome, string $descrizione): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO `Questionari` (Nome, Descrizione) VALUES (:nome, :descrizione)"
        );
        $stmt->execute([
            'nome' => $nome,
            'descrizione' => $descrizione
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function updateQuestionario(int $id, string $nome, string $descrizione): void
    {
        $stmt = $this->db->prepare(
            "UPDATE `Questionari` SET Nome = :nome, Descrizione = :descrizione WHERE idQuest = :id"
        );
        $stmt->execute([
            'id' => $id,
            'nome' => $nome,
            'descrizione' => $descrizione
        ]);
    }

    public function updateDomanda(int $idDomanda, string $domanda): void
    {
        $stmt = $this->db->prepare(
            "UPDATE `Domande` SET Domanda = :domanda WHERE idDomanda = :idDomanda"
        );
        $stmt->execute(['idDomanda' => $idDomanda, 'domanda' => $domanda]);
    }

    public function updateRisultato(int $idRisultato, string $descrizione, int $fasciaMin, int $fasciaMax): void
    {
        $stmt = $this->db->prepare(
            "UPDATE `Risultati` SET Descrizione = :descrizione, Fascia_Min = :fasciaMin, Fascia_Max = :fasciaMax WHERE idRisultato = :idRisultato"
        );
        $stmt->execute([
            'idRisultato' => $idRisultato,
            'descrizione' => $descrizione,
            'fasciaMin' => $fasciaMin,
            'fasciaMax' => $fasciaMax
        ]);
    }

    public function deleteDomanda(int $idDomanda): void
    {
        $stmt = $this->db->prepare(
            "DELETE FROM `Domande` WHERE idDomanda = :idDomanda"
        );
        $stmt->execute(['idDomanda' => $idDomanda]);
    }

    public function deleteRisultato(int $idRisultato): void
    {
        $stmt = $this->db->prepare(
            "DELETE FROM `Risultati` WHERE idRisultato = :idRisultato"
        );
        $stmt->execute(['idRisultato' => $idRisultato]);
    }
}