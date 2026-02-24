<?php
declare(strict_types=1);

namespace Api\Services;

use Api\Dto\Utente;
use Api\Repositories\UtentiRepository;

final class UtentiService
{
    public function __construct(private UtentiRepository $repo) {}

    /** @return Utente[] */
    public function list(): array
    {
        return $this->repo->getAll();
    }

    public function get(int $id): Utente
    {
        $u = $this->repo->findById($id);
        if (!$u) throw new \RuntimeException('Utente non trovato');
        return $u;
    }

    public function create(array $input): Utente
    {
        $utente = $this->mapInputToUtente($input, 0);

        if ($this->repo->findByEmail($utente->Email)) {
            throw new \RuntimeException('Email già registrata');
        }

        // Hash password
        $utente = $utente->withPassword(password_hash($utente->Password, PASSWORD_DEFAULT));

        return $this->repo->create($utente);
    }

    public function update(int $id, array $input): Utente
    {
        $existing = $this->repo->findById($id);
        if (!$existing) throw new \RuntimeException('Utente non trovato');

        // Se password non presente, mantieni quella attuale
        $password = (string)($input['Password'] ?? '');
        $finalPasswordHash = $existing->Password;

        if ($password !== '') {
            $finalPasswordHash = password_hash($password, PASSWORD_DEFAULT);
        }

        $utente = new Utente(
            $id,
            (string)($input['Nome'] ?? $existing->Nome),
            (string)($input['Cognome'] ?? $existing->Cognome),
            (string)($input['Email'] ?? $existing->Email),
            $finalPasswordHash,
            (string)($input['Cell'] ?? $existing->Cell),
            (bool)($input['IsAdmin'] ?? $existing->IsAdmin)
        );

        return $this->repo->update($id, $utente) ?? throw new \RuntimeException('Update fallita');
    }

    public function delete(int $id): void
    {
        if (!$this->repo->delete($id)) {
            throw new \RuntimeException('Utente non trovato');
        }
    }

    // --- Accesso area riservata ---

    public function register(array $input): Utente
    {
        // In questo scenario, registrazione = create con IsAdmin false di default
        $input['IsAdmin'] = false;
        return $this->create($input);
    }

    public function login(string $email, string $password): array
    {
        $u = $this->repo->findByEmail($email);
        if (!$u || !password_verify($password, $u->Password)) {
            throw new \RuntimeException('Credenziali non valide');
        }

        // Fittizio: ritorniamo un token fake (poi lo sostituisci con JWT vero)
        $fakeToken = base64_encode('fake-token-for-user-' . $u->ID);

        return [
            'token' => $fakeToken,
            'utente' => $u, // jsonSerialize nasconde password
        ];
    }

    public function recoverPassword(string $email): array
    {
        $u = $this->repo->findByEmail($email);
        if (!$u) {
            // Per sicurezza, risposta generica anche se non esiste
            return ['ok' => true];
        }

        // Fittizio: in futuro invierai email con token reset
        return [
            'ok' => true,
            'message' => 'Email di recupero password simulata',
        ];
    }

    private function mapInputToUtente(array $input, int $id): Utente
    {
        $required = ['Nome', 'Cognome', 'Email', 'Password', 'Cell'];
        foreach ($required as $k) {
            if (!isset($input[$k]) || trim((string)$input[$k]) === '') {
                throw new \RuntimeException("Campo obbligatorio mancante: {$k}");
            }
        }

        return new Utente(
            $id,
            (string)$input['Nome'],
            (string)$input['Cognome'],
            (string)$input['Email'],
            (string)$input['Password'],
            (string)$input['Cell'],
            (bool)($input['IsAdmin'] ?? false)
        );
    }
}