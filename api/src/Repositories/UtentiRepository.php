<?php
declare(strict_types=1);

namespace Api\Repositories;

use Api\Dto\Utente;
use PDO;
class UtentiRepository
{
    public function __construct(private PDO $pdo) {}

    public function getPacchettiServiziByUtenteId(int $utenteId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT	
                s.idServizio, s.servizio, p.Pacchetto   
            FROM
                Pacchetti_Utenti pu
            JOIN 
                Pacchetti p on pu.idPacchetto = p.idPacchetto
            JOIN
                Pacchetti_Servizi ps on ps.idPacchetto = p.idPacchetto
            JOIN 
                Servizi s on ps.idServizio = s.idServizio
            WHERE
                pu.idUtente = :idUtente
                and
                pu.DataScadenza is null or pu.DataScadenza >= NOW();  
        ");

        $stmt->execute(['idUtente' => $utenteId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getQuestionariByUtenteId(int $utenteId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT	
               q.idQuest, q.Nome, q.Domande_Max
            FROM
                Pacchetti_Utenti pu
            JOIN 
                Pacchetti p on pu.idPacchetto = p.idPacchetto
            JOIN
                Pacchetti_Servizi ps on ps.idPacchetto = p.idPacchetto
            JOIN 
                Servizi s on ps.idServizio = s.idServizio
            JOIN
                Questionari q on q.idServizio = s.idServizio    
            WHERE
                pu.idUtente = :idUtente
                and
                pu.DataScadenza is null or pu.DataScadenza >= NOW();  
        ");

        $stmt->execute(['idUtente' => $utenteId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByEmail(string $email): ?Utente
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM Utenti WHERE email = :email LIMIT 1
        ");

        $stmt->execute(['email' => $email]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return $this->mapRowToDto($row);
    }

    public function create(Utente $utente): Utente
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO Utenti
            (nome,cognome,email,password,data_nascita,luogo_nascita,sesso,cellulare)
            VALUES
            (:nome,:cognome,:email,:password,:data_nascita,:luogo_nascita,:sesso,:cellulare)
        ");

        $stmt->execute([
            'nome' => $utente->Nome,
            'cognome' => $utente->Cognome,
            'email' => $utente->Email,
            'password' => $utente->Password,
            'data_nascita' => $utente->DataNascita,
            'luogo_nascita' => $utente->LuogoNascita,
            'sesso' => $utente->Sesso,
            'cellulare' => $utente->Cellulare
        ]);

        $id = (int)$this->pdo->lastInsertId();

        return new Utente(
            $id,
            $utente->Nome,
            $utente->Cognome,
            $utente->Email,
            $utente->Password,
            $utente->DataNascita,
            $utente->LuogoNascita,
            $utente->Sesso,
            $utente->Cellulare
        );
    }

    private function mapRowToDto(array $row): Utente
    {
        return new Utente(
            (int)$row['id'],
            $row['nome'],
            $row['cognome'],
            $row['email'],
            $row['password'],
            $row['data_nascita'],
            $row['luogo_nascita'],
            $row['sesso'],
            $row['cellulare']
        );
    }
}

 