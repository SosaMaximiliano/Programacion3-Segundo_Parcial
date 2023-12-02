<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once './Model/AuthJWT.php';
require_once './Model/Cliente.php';
require_once './Utils/Logger.php';

class AuthJWTController
{
    public function CrearTokenLogin(Request $request, Response $response, $args)
    {
        $parametros = $request->getParsedBody();
        $user = $parametros['User'];
        $pass = $parametros['Pass'];
        $usuario = Usuario::TraerUsuarioPorUserPass($user, $pass);
        if ($usuario != null)
        {
            if (password_verify($pass, $usuario[0]->Pass))
            {
                $datos = array(
                    'ID' => $usuario[0]->ID,
                    'User' => $usuario[0]->User,
                    'Pass' => $usuario[0]->Pass,
                    'Rol' => $usuario[0]->Rol
                );
                $token = AuthJWT::CrearToken($datos);
                $payload = json_encode($token);
                $response->getBody()->write($payload);
                Logger::Log("Login", $usuario[0]->ID, "Log");
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
        Logger::Log("Login", $usuario[0]->ID, "LogTodos");
        return $response->withHeader('Content-Type', 'application/json');
    }
}
