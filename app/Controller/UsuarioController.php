<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

include_once './Model/Cliente.php';
include_once './Model/Reserva.php';
include_once './Model/Usuario.php';

class UsuarioController
{
    public static function AltaUsuario(Request $request, Response $response, $args)
    {
        $parametros = $request->getParsedBody();
        $user = $parametros['User'];
        $pass = $parametros['Pass'];
        $rol = $parametros['Rol'];

        try
        {
            Usuario::AltaUsuario($user, $pass, $rol);
            $payload = json_encode("El usuario fue ingresado correctamente");
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
        catch (Exception $e)
        {
            $payload = json_encode("Error al ingresar usuario. {$e->getMessage()}");
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }
}
