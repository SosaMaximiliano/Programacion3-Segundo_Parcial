<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

include_once './Model/Reserva.php';
include_once './Utils/Logger.php';

class ReservaController
{
    public static function AltaReserva(Request $request, Response $response, $args)
    {
        #HACER VALIDACIONES
        $parametros = $request->getParsedBody();
        $tipoCliente = $parametros['TipoCliente'];
        $idCliente = $parametros['ID_Cliente'];
        $ingreso = $parametros['Ingreso'];
        $salida = $parametros['Salida'];
        $tipoHabitacion = $parametros['TipoHabitacion'];
        $importe = $parametros['Importe'];
        $archivo = $request->getUploadedFiles()['Imagen'];
        $nombreArchivo = NULL;
        try
        {
            $idReserva = Reserva::AltaReserva($tipoCliente, $idCliente, $ingreso, $salida, $tipoHabitacion, $importe);
            Logger::LogOK("AltaReserva", $idCliente);
            switch ($tipoCliente)
            {
                case 'Individual':
                    $nombreArchivo = "IN$idCliente$idReserva";
                    break;
                case 'Corporativo':
                    $nombreArchivo = "CO$idCliente$idReserva";
                    break;
                default:
                    $nombreArchivo = "ERR$idCliente$idReserva";
                    break;
            }

            try
            {
                Reserva::CargarImagen($nombreArchivo, $archivo);
                $payload = json_encode("La reserva $nombreArchivo fue ingresada correctamente");
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json');
            }
            catch (Exception $e)
            {
                $payload = json_encode("Error al imagen. {$e->getMessage()}");
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json');
            }
        }
        catch (Exception $e)
        {
            $payload = json_encode("Error al ingresar reserva. {$e->getMessage()}");
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
        finally
        {
            Logger::LogTodos("AltaReserva");
        }
    }

    public static function BajaReserva(Request $request, Response $response, $args)
    {
        $idReserva = $request->getQueryParams()['ID_Reserva'];
        if (Reserva::ExisteReserva($idReserva))
        {
            try
            {
                Reserva::BajaReserva($idReserva);
                Logger::LogOK("BajaReserva", $idReserva);
                $payload = json_encode("La reserva $idReserva fue cancelada");
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json');
            }
            catch (Exception $e)
            {
                $payload = json_encode("Error al cancelar reserva. {$e->getMessage()}");
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json');
            }
            finally
            {
                Logger::LogTodos("BajaReserva");
            }
        }
        else
        {
            $payload = json_encode("Numero de reserva no existe o fue dada de baja");
            $response->getBody()->write($payload);
            Logger::LogTodos("BajaReserva");
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    public static function ModificarReserva(Request $request, Response $response, $args)
    {
        $parametros = $request->getParsedBody();
        $idReserva = $parametros['ID_Reserva'];
        $idCliente = $parametros['ID_Cliente'];
        $tipoCliente = $parametros['TipoCliente'];
        $tipoHabitacion = $parametros['TipoHabitacion'];
        $ingreso = $parametros['Ingreso'];
        $salida = $parametros['Salida'];
        $importe = $parametros['Importe'];
        $formaPago = $parametros['Pago'];
        $motivo = $parametros['Comentario'];
        if (Reserva::ExisteReserva($idReserva))
        {
            try
            {
                Reserva::ModificarReserva($idReserva, $motivo, $tipoCliente, $idCliente, $ingreso, $salida, $tipoHabitacion, $importe, $formaPago);
                Logger::LogOK("ModificarReserva", $idReserva);
                $payload = json_encode("La reserva $idReserva fue modificada");
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json');
            }
            catch (Exception $e)
            {
                $payload = json_encode("Error al modificar reserva. {$e->getMessage()}");
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json');
            }
            finally
            {
                Logger::LogTodos("ModificarReserva");
            }
        }
        else
        {
            Logger::LogTodos("ModificarReserva");
            $payload = json_encode("Numero de reserva no existe o fue dada de baja");
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    public static function ListarReservasA(Request $request, Response $response, $args)
    {
        $fecha = $request->getQueryParams()['fecha'];
        try
        {
            $reservas = Reserva::ListarReservasA($fecha);
            Logger::LogOK("ListarReservasA", NULL);
            $payload = json_encode($reservas);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
        catch (Exception $e)
        {
            $payload = json_encode("Error al buscar reservas. {$e->getMessage()}");
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
        finally
        {
            Logger::LogTodos("ListarReservasA");
        }
    }

    public static function ListarReservasB(Request $request, Response $response, $args)
    {
        $idCliente = $request->getQueryParams()['idCliente'];
        try
        {
            $reservas = Reserva::ListarReservasB($idCliente);
            Logger::LogOK("ListarReservasB", NULL);
            $payload = json_encode($reservas);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
        catch (Exception $e)
        {
            $payload = json_encode("Error al buscar reservas. {$e->getMessage()}");
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
        finally
        {
            Logger::LogTodos("ListarReservasB");
        }
    }

    public static function ListarReservasC(Request $request, Response $response, $args)
    {
        $desde = $request->getQueryParams()['desde'];
        $hasta = $request->getQueryParams()['hasta'];
        try
        {
            $reservas = Reserva::ListarReservasC($desde, $hasta);
            Logger::LogOK("ListarReservasC", NULL);
            $payload = json_encode($reservas);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
        catch (Exception $e)
        {
            $payload = json_encode("Error al buscar reservas. {$e->getMessage()}");
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
        finally
        {
            Logger::LogTodos("ListarReservasC");
        }
    }

    public static function ListarReservasD(Request $request, Response $response, $args)
    {
        $tipoHabitacion = $request->getQueryParams()['tipoHabitacion'];
        try
        {
            $reservas = Reserva::ListarReservasD($tipoHabitacion);
            Logger::LogOK("ListarReservasD", NULL);
            $payload = json_encode($reservas);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
        catch (Exception $e)
        {
            $payload = json_encode("Error al buscar reservas. {$e->getMessage()}");
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
        finally
        {
            Logger::LogTodos("ListarReservasD");
        }
    }

    public static function ListarReservasA2(Request $request, Response $response, $args)
    {
        $fecha = $request->getQueryParams()['fecha'];
        try
        {
            $reservas = Reserva::ListarReservasA2($fecha);
            Logger::LogOK("ListarReservasA2", NULL);
            $payload = json_encode($reservas);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
        catch (Exception $e)
        {
            $payload = json_encode("Error al buscar reservas. {$e->getMessage()}");
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
        finally
        {
            Logger::LogTodos("ListarReservasA2");
        }
    }

    public static function ListarReservasB2(Request $request, Response $response, $args)
    {
        $idCliente = $request->getQueryParams()['ID_Cliente'];
        try
        {
            $reservas = Reserva::ListarReservasB2($idCliente);
            Logger::LogOK("ListarReservasB2", NULL);
            $payload = json_encode($reservas);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
        catch (Exception $e)
        {
            $payload = json_encode("Error al buscar reservas. {$e->getMessage()}");
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
        finally
        {
            Logger::LogTodos("ListarReservasB2");
        }
    }

    public static function ListarReservasC2(Request $request, Response $response, $args)
    {
        $desde = $request->getQueryParams()['desde'];
        $hasta = $request->getQueryParams()['hasta'];
        try
        {
            $reservas = Reserva::ListarReservasC2($desde, $hasta);
            Logger::LogOK("ListarReservasC2", NULL);
            $payload = json_encode($reservas);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
        catch (Exception $e)
        {
            $payload = json_encode("Error al buscar reservas. {$e->getMessage()}");
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
        finally
        {
            Logger::LogTodos("ListarReservasC2");
        }
    }

    public static function ListarReservasD2(Request $request, Response $response, $args)
    {
        $tipoCliente = $request->getQueryParams()['TipoCliente'];
        try
        {
            $reservas = Reserva::ListarReservasD2($tipoCliente);
            Logger::LogOK("ListarReservasD2", NULL);
            $payload = json_encode($reservas);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
        catch (Exception $e)
        {
            $payload = json_encode("Error al buscar reservas. {$e->getMessage()}");
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
        finally
        {
            Logger::LogTodos("ListarReservasD2");
        }
    }

    public static function ListarReservasE(Request $request, Response $response, $args)
    {
        $idCliente = $request->getQueryParams()['ID_Cliente'];
        try
        {
            $reservas = Reserva::ListarReservasE($idCliente);
            Logger::LogOK("ListarReservasE", NULL);
            $payload = json_encode($reservas);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
        catch (Exception $e)
        {
            $payload = json_encode("Error al buscar reservas. {$e->getMessage()}");
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
        finally
        {
            Logger::LogTodos("ListarReservasE");
        }
    }

    public static function ListarReservasF(Request $request, Response $response, $args)
    {
        $pago = $request->getQueryParams()['Pago'];
        try
        {
            $reservas = Reserva::ListarReservasF($pago);
            Logger::LogOK("ListarReservasF", NULL);
            $payload = json_encode($reservas);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
        catch (Exception $e)
        {
            $payload = json_encode("Error al buscar reservas. {$e->getMessage()}");
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
        finally
        {
            Logger::LogTodos("ListarReservasF");
        }
    }
}
