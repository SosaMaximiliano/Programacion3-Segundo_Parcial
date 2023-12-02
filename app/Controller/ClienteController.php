<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

include_once './Model/Cliente.php';
include_once './Model/Reserva.php';
include_once './Utils/Utils.php';
include_once './Utils/Logger.php';

class ClienteController
{
    public static function AltaCliente(Request $request, Response $response, $args)
    {
        $tipoAux = '';
        $parametros = $request->getParsedBody();
        $nombre = $parametros['Nombre'];
        $apellido = $parametros['Apellido'];
        $tipoDoc = $parametros['TipoDocumento'];
        $nroDoc = $parametros['NroDocumento'];
        $clave = $parametros['Clave'];
        $email = $parametros['Email'];
        $tel = $parametros['Telefono'];
        $pais = $parametros['Pais'];
        $ciudad = $parametros['Ciudad'];
        $tipo = $parametros['TipoCliente'];
        $archivo = $request->getUploadedFiles()['Imagen'];

        if (Cliente::ExisteCliente($nombre, $apellido, $tipoDoc, $nroDoc))
        {
            Logger::LogTodos("AltaCliente");
            throw new Exception("El cliente ya se encuentra ingresado");
        }
        try
        {
            switch ($tipo)
            {
                case 'Individual':
                    $tipoAux = 'INDI-' . strtoupper($tipoDoc);
                    break;
                case 'Corporativo':
                    $tipoAux = 'CORPO-' . strtoupper($tipoDoc);
                    break;
            }

            $idCliente = Cliente::AltaCliente($nombre, $apellido, $tipoDoc, $nroDoc, $clave, $email, $tel, $pais, $ciudad, $tipoAux);
            Logger::LogOK("AltaCliente", $idCliente);
            try
            {
                Cliente::CargarImagen($idCliente, $tipo, $archivo);
                $payload = json_encode("El cliente $nombre $apellido fue ingresado correctamente con ID $idCliente");
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json');
            }
            catch (Exception $e)
            {
                $payload = json_encode("Error al subir imagen. {$e->getMessage()}");
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json');
            }
        }
        catch (Exception $e)
        {
            $payload = json_encode("Error al ingresar cliente. {$e->getMessage()}");
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
        finally
        {
            Logger::LogTodos("AltaCliente");
        }
    }

    public static function BajaCliente(Request $request, Response $response, $args)
    {
        $parametros = $request->getQueryParams();
        $idCliente = $parametros['ID'];
        $tipoCliente = $parametros['TipoCliente'];
        $cliente = Cliente::TraeClientePorID($idCliente);
        if (count($cliente) > 0 && $cliente[0]->Estado == 'Activo')
        {
            $tipoAux = '';
            switch ($tipoCliente)
            {
                case 'Individual':
                    $tipoAux = 'INDI-' . strtoupper($cliente[0]->TipoDocumento);
                    break;
                case 'Corporativo':
                    $tipoAux = 'CORPO-' . strtoupper($cliente[0]->TipoDocumento);
                    break;
            }

            if (!($tipoAux == $cliente[0]->TipoCliente))
            {
                $payload = json_encode("El tipo de cliente no coincide con el ID");
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json');
            }
            try
            {
                Cliente::BajaCliente($idCliente, $cliente[0]->TipoCliente);
                Cliente::MoverImagen($idCliente);
                $reservas = Reserva::TraerReservaPorCliente($idCliente);
                if (!empty($reservas))
                {
                    foreach ($reservas as $e)
                    {
                        Reserva::BajaReserva($e->ID);
                    }
                }
                $payload = json_encode("El cliente $idCliente fue dado de baja");
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json');
            }
            catch (Exception $e)
            {
                $payload = json_encode("Error al dar de baja. {$e->getMessage()}");
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json');
            }
            finally
            {
                Logger::LogTodos("BajaCliente");
            }
        }
        else
        {
            $payload = json_encode("El cliente $idCliente no existe o fue dado de baja");
            $response->getBody()->write($payload);
            Logger::LogTodos("BajaCliente");
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    public static function ModificarCliente(Request $request, Response $response, $args)
    {
        $parametros = $request->getParsedBody();
        $idCliente = $parametros['ID'];
        $nombre = $parametros['Nombre'];
        $apellido = $parametros['Apellido'];
        $tipoDoc = $parametros['TipoDocumento'];
        $nroDoc = $parametros['NroDocumento'];
        $email = $parametros['Email'];
        $tel = $parametros['Telefono'];
        $pais = $parametros['Pais'];
        $ciudad = $parametros['Ciudad'];
        $tipoCliente = $parametros['TipoCliente'];

        $cliente = Cliente::TraeClientePorID($idCliente);
        if (count($cliente) > 0 && $cliente[0]->Estado == 'Activo')
        {
            try
            {
                Cliente::ModificarCliente($idCliente, $nombre, $apellido, $tipoDoc, $nroDoc, $email, $tel, $pais, $ciudad, $tipoCliente);
                $payload = json_encode("El cliente $idCliente fue dado de baja");
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json');
            }
            catch (Exception $e)
            {
                $payload = json_encode("Error al dar de baja. {$e->getMessage()}");
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json');
            }
            finally
            {
                Logger::LogTodos("modificarCliente");
            }
        }
        else
        {
            $payload = json_encode("El cliente no existe o fue dado de baja.");
            $response->getBody()->write($payload);
            Logger::LogTodos("ModificarCliente");
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    public static function ConsultarCliente(Request $request, Response $response, $args)
    {
        $idCliente = $request->getParsedBody()['ID_Cliente'];
        $tipo = $request->getParsedBody()['Tipo_Cliente'];
        try
        {
            $cliente = Cliente::ConsultarCliente($idCliente, $tipo);
            $payload = json_encode($cliente);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
        catch (Exception $ex)
        {
            $payload = json_encode("Error al consultar cliente. {$ex->getMessage()}");
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
        finally
        {
            Logger::LogTodos("ConsultarCliente");
        }
    }

    public static function ListarClientes(Request $request, Response $response, $args)
    {
        try
        {
            $cliente = Cliente::ListarClientes();
            $payload = json_encode($cliente);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
        catch (Exception $ex)
        {
            $payload = json_encode("Error al listar clientes. {$ex->getMessage()}");
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
        finally
        {
            Logger::LogTodos("ListarClientes");
        }
    }
}
