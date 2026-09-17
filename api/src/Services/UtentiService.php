<?php
declare(strict_types=1);

namespace Api\Services;

use Api\Dto\Utente;
use Api\Repositories\UtentiRepository;
 use Firebase\JWT\JWT;

final class UtentiService
{
    public function __construct(private UtentiRepository $repo, private QuestionariService $questionariService,
        private string $jwtSecret,
        private string $jwtIssuer,
        private int $jwtTtl
    ) {}

    public function getPacchettiServiziByUtenteId(int $utenteId): array
    {
        return $this->repo->getPacchettiServiziByUtenteId($utenteId);

    }

  public function register(array $input): Utente
{
    if ($this->repo->findByEmail($input['Email'])) {
        throw new \RuntimeException("Email già registrata");
    }

    $hash = password_hash($input['Password'], PASSWORD_DEFAULT);

    $utente = new Utente(
        0,
        $input['Nome'],
        $input['Cognome'],
        $input['Email'],
        $hash,
        $input['DataNascita'],
        $input['LuogoNascita'] ?? null,
        $input['Sesso'] ?? null,
        $input['Cellulare'] ?? null
    );

    return $this->repo->create($utente);
}

   


public function login(string $email, string $password): array
{
    $u = $this->repo->findByEmail($email);

    if (!$u || !password_verify($password, $u->Password)) {
        throw new \RuntimeException("Credenziali non valide");
    }

    $now = time();

    $payload = [
        'iss' => $this->jwtIssuer,
        'iat' => $now,
        'exp' => $now + $this->jwtTtl,
        'sub' => $u->ID,
        'email' => $u->Email,
        'type' => 'user'
    ];

    $jwt = JWT::encode($payload, $this->jwtSecret, 'HS256');

    return [
        'token' => $jwt,
        'utente' => $u,
        'servizi' => $this->getPacchettiServiziByUtenteId($u->ID)
    ];
}

public function getQuestionariByUtenteId(int $utenteId): array
{
     
    $questionari = $this->repo->getQuestionariByUtenteId($utenteId);
    $questionari = array_map(fn($q) => $this->questionariService->getById((int)$q['idQuest'] ), $questionari);

    
    return $questionari; 
}


}