<?php
declare(strict_types=1);

namespace Api\Controllers;
use Api\Services\PacchettiService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class PacchettiController
{
    public function __construct(private PacchettiService $service) {}

    public function getAll(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = $this->service->getAll();

        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getById(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = (int)$args['id'];
        $data = $this->service->getById($id);

        if (!$data) {
            $response->getBody()->write(json_encode(['error' => 'Pacchetto non trovato'], JSON_UNESCAPED_UNICODE));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function addServizio(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $pacchettoId = (int)$args['id'];
        $input = json_decode((string)$request->getBody(), true);
        $servizioId = (int)$input['idServizio'] ?? 0;

        if (!$servizioId) {
            $response->getBody()->write(json_encode(['error' => 'ID del servizio mancante'], JSON_UNESCAPED_UNICODE));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $data = $this->service->addServizio($pacchettoId, $servizioId);

        if (!$data) {
            $response->getBody()->write(json_encode(['error' => 'Pacchetto o servizio non trovato'], JSON_UNESCAPED_UNICODE));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function removeServizio(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $pacchettoId = (int)$args['id'];
        $input = json_decode((string)$request->getBody(), true);
        $servizioId = (int)$input['idServizio'] ?? 0;

        if (!$servizioId) {
            $response->getBody()->write(json_encode(['error' => 'ID del servizio mancante'], JSON_UNESCAPED_UNICODE));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $data = $this->service->removeServizio($pacchettoId, $servizioId);

        if (!$data) {
            $response->getBody()->write(json_encode(['error' => 'Pacchetto o servizio non trovato'], JSON_UNESCAPED_UNICODE));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
?>
