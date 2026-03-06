<?php
declare(strict_types=1);

namespace Api\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;

final class JwtMiddleware
{
    public function __construct(private string $secret) {}

    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    { 
        $authHeader = $request->getHeaderLine('Authorization');

        if (!preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            return $this->unauthorized();
        }

        $token = $matches[1];

       try {

                $decoded = JWT::decode($token, new Key($this->secret, 'HS256'));

                $request = $request->withAttribute('user', $decoded);

            } catch (\Exception $e) {
                return $this->unauthorized();
            }

        return $handler->handle($request);
    }

    private function unauthorized(): ResponseInterface
    {
        $response = new Response();
        $response->getBody()->write(json_encode(['error' => 'Unauthorized']));
        return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
    }
}