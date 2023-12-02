<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class CheckGerenteMW
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $header = $request->getHeaderLine(("Authorization"));
        $token = trim(explode("Bearer", $header)[1]);
        $response = new Response();
        try
        {
            $data = AuthJWT::ObtenerPayload($token);
            if ($data->data->Rol == 'Gerente')
                $response = $handler->handle($request);
            else
                $response->getBody()->write(json_encode("El usuario no tiene permisos"));
        }
        catch (Exception $e)
        {
            $response->getBody()->write(json_encode("Error al obtener datos. {$e->getMessage()}"));
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
}
