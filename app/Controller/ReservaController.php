<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

include_once './Model/Reserva.php';

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
    }

    public static function BajaReserva(Request $request, Response $response, $args)
    {
        $idReserva = $request->getQueryParams()['ID_Reserva'];
        if (Reserva::ExisteReserva($idReserva))
        {
            try
            {
                Reserva::BajaReserva($idReserva);
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
        }
        else
        {
            $payload = json_encode("Numero de reserva no existe o fue dada de baja");
            $response->getBody()->write($payload);
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
        }
        else
        {
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
    }

    public static function ListarReservasB(Request $request, Response $response, $args)
    {
        $idCliente = $request->getQueryParams()['idCliente'];
        try
        {
            $reservas = Reserva::ListarReservasB($idCliente);
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
    }

    public static function ListarReservasC(Request $request, Response $response, $args)
    {
        $desde = $request->getQueryParams()['desde'];
        $hasta = $request->getQueryParams()['hasta'];
        try
        {
            $reservas = Reserva::ListarReservasC($desde, $hasta);
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
    }

    public static function ListarReservasD(Request $request, Response $response, $args)
    {
        $tipoHabitacion = $request->getQueryParams()['tipoHabitacion'];
        try
        {
            $reservas = Reserva::ListarReservasD($tipoHabitacion);
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
    }

    public static function ListarReservasA2(Request $request, Response $response, $args)
    {
        $fecha = $request->getQueryParams()['fecha'];
        try
        {
            $reservas = Reserva::ListarReservasA2($fecha);
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
    }

    public static function ListarReservasB2(Request $request, Response $response, $args)
    {
        $idCliente = $request->getQueryParams()['ID_Cliente'];
        try
        {
            $reservas = Reserva::ListarReservasB2($idCliente);
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
    }

    public static function ListarReservasC2(Request $request, Response $response, $args)
    {
        $desde = $request->getQueryParams()['desde'];
        $hasta = $request->getQueryParams()['hasta'];
        try
        {
            $reservas = Reserva::ListarReservasC2($desde, $hasta);
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
    }

    public static function ListarReservasD2(Request $request, Response $response, $args)
    {
        $tipoCliente = $request->getQueryParams()['TipoCliente'];
        try
        {
            $reservas = Reserva::ListarReservasD2($tipoCliente);
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
    }

    public static function ListarReservasE(Request $request, Response $response, $args)
    {
        $idCliente = $request->getQueryParams()['ID_Cliente'];
        try
        {
            $reservas = Reserva::ListarReservasE($idCliente);
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
    }

    public static function ListarReservasF(Request $request, Response $response, $args)
    {
        $pago = $request->getQueryParams()['Pago'];
        try
        {
            $reservas = Reserva::ListarReservasF($pago);
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
    }
}
