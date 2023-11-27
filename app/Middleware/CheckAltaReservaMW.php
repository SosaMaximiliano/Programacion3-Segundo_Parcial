<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

include_once './Utils/ValidadorReserva.php';
include_once './Model/Reserva.php';

class CheckAltaReservaMW
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $parametros = $request->getParsedBody();
        if (ValidadorReserva::ValidarDatosIngresados($parametros))
        {
            $tiposImagenPermitidos = ['jpg', 'jpeg', 'png', 'gif'];
            $archivo = $request->getUploadedFiles()['Imagen'];
            $extension = pathinfo($archivo->getClientFilename(), PATHINFO_EXTENSION);
            if (!in_array(strtolower($extension), $tiposImagenPermitidos))
                throw new Exception("Tipo de imagen no permitido");
            $response = $handler->handle($request);
        }
        else
        {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'Los datos no son validos'));
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
}
