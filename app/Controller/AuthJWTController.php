<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once './Model/AuthJWT.php';
require_once './Model/Cliente.php';

class AuthJWTController
{
    public function CrearTokenLogin(Request $request, Response $response, $args)
    {
        $parametros = $request->getParsedBody();
        $idCliente = $parametros['ID'];
        $clave = $parametros['Clave'];
        $usuario = Cliente::TraeClientePorID($idCliente);
        if ($usuario != null)
        {
            if (password_verify($clave, $usuario[0]->Clave))
            {
                $datos = array(
                    'ID' => $usuario[0]->ID,
                    'Clave' => $usuario[0]->Clave,
                );
                $token = AuthJWT::CrearToken($datos);
                $payload = json_encode($token);
                $response->getBody()->write($payload);
            }
            else
            {
                $response->getBody()->write(json_encode(array("mensaje" => "Error, verifique la información")));
            }
        }
        else
        {
            $response->getBody()->write(json_encode(array("mensaje" => "Error, verifique la información")));
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
}
