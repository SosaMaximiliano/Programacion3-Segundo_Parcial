<?php

include_once 'Cliente.php';


class Reserva
{
    public static function AltaReserva($tipoCliente, $idCliente, $ingreso, $salida, $tipoHabitacion, $importe)
    {
        $fecha = new DateTime();
        $formaPago = 'Efectivo';
        $estado = 'Activo';
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "INSERT INTO Reserva (TipoCliente,ID_Cliente,Ingreso,Salida,TipoHabitacion,ImporteTotal,Pago,FechaAlta,Estado) 
            VALUES (:tipoCliente,:idCliente,:ingreso,:salida,:habitacion,:importe,:pago,:fechaAlta,:estado)"
        );
        $consulta->bindValue(':tipoCliente', $tipoCliente, PDO::PARAM_STR);
        $consulta->bindValue(':idCliente', $idCliente, PDO::PARAM_STR);
        $consulta->bindValue(':ingreso', $ingreso, PDO::PARAM_STR);
        $consulta->bindValue(':salida', $salida, PDO::PARAM_STR);
        $consulta->bindValue(':habitacion', $tipoHabitacion, PDO::PARAM_STR);
        $consulta->bindValue(':importe', $importe, PDO::PARAM_INT);
        $consulta->bindValue(':pago', $formaPago, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->bindValue(':fechaAlta', $fecha->format('Y-m-d,H:i:s'), PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function BajaReserva($idReserva)
    {
        $fecha = new DateTime();
        $estado = 'Cancelado';
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "UPDATE Reserva SET 
            Estado = :estado, 
            FechaBaja = :fechaBaja 
            WHERE ID = :idReserva"
        );
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->bindValue(':idReserva', $idReserva, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', $fecha->format('Y-m-d,H:i:s'), PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function ModificarReserva($idReserva, $motivo, $tipoCliente, $idCliente, $ingreso, $salida, $tipoHabitacion, $importe, $formaPago)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "UPDATE Reserva SET 
            Comentario = :motivo, 
            TipoCliente = :tipoCliente, 
            Ingreso = :ingreso,
            Salida = :salida,
            TipoHabitacion = :tipoHabitacion,
            ImporteTotal = :importe,
            Pago = :pago
            WHERE ID = :idReserva"
        );
        $consulta->bindValue(':motivo', $motivo, PDO::PARAM_STR);
        $consulta->bindValue(':tipoCliente', $tipoCliente, PDO::PARAM_STR);
        $consulta->bindValue(':idReserva', $idReserva, PDO::PARAM_INT);
        $consulta->bindValue(':ingreso', $ingreso, PDO::PARAM_STR);
        $consulta->bindValue(':salida', $salida, PDO::PARAM_STR);
        $consulta->bindValue(':tipoHabitacion', $tipoHabitacion, PDO::PARAM_STR);
        $consulta->bindValue(':importe', $importe, PDO::PARAM_INT);
        $consulta->bindValue(':pago', $formaPago, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function CargarImagen($idReserva, $archivo)
    {
        $extension = pathinfo($archivo->getClientFilename(), PATHINFO_EXTENSION);
        $archivo->moveTo("./ImagenesDeReservas/$idReserva.$extension");
        return true;
    }

    public static function BuscarReservaPorID($idReserva)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * FROM Reserva WHERE ID = :idReserva"
        );
        $consulta->bindValue(':idReserva', $idReserva, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Reserva');
    }

    public static function ListarReservasVigentes()
    {
        $hoy = date('Y-m-d');
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * FROM Reserva WHERE Salida  >= {$hoy} AND Estado = 'Activo'"
        );
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Reserva');
    }

    public static function ListarReservasA($fecha = NULL)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT SUM(ImporteTotal) AS Total, TipoHabitacion AS Habitacion
            FROM Reserva 
            WHERE (FechaAlta = :fecha OR FechaAlta = CURRENT_DATE - 1)
            ORDER BY TipoHabitacion"

        );
        $consulta->bindValue(':fecha', $fecha, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Reserva');
    }

    public static function ListarReservasB($idCliente)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * FROM Reserva
            WHERE ID_Cliente = :idCliente"
        );
        $consulta->bindValue(':idCliente', $idCliente, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Reserva');
    }

    public static function ListarReservasC($desde, $hasta)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * FROM Reserva
            WHERE FechaALta BETWEEN :desde AND :hasta ORDER BY FechaAlta;"
        );
        $consulta->bindValue(':desde', $desde, PDO::PARAM_STR);
        $consulta->bindValue(':hasta', $hasta, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Reserva');
    }

    public static function ListarReservasD($tipoHabitacion)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * FROM Reserva
            WHERE TipoHabitacion = :tipoHabitacion"
        );
        $consulta->bindValue(':tipoHabitacion', $tipoHabitacion, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Reserva');
    }

    public static function ExisteReserva($idReserva)
    {
        $reservas = self::ListarReservasVigentes();
        foreach ($reservas as $e)
        {
            if ($e->ID == $idReserva)
                return true;
        }
        return false;
    }

    public static function ExisteReservaPorCliente($idCliente)
    {
        $reservas = self::ListarReservasVigentes();
        foreach ($reservas as $e)
        {
            if ($e->ID_Cliente == $idCliente)
                return true;
        }
        return false;
    }

    public static function TraerReservaPorCliente($idCliente)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * FROM Reserva
            WHERE ID_Cliente = :idCliente 
            AND Estado = 'Activo'"
        );
        $consulta->bindValue(':idCliente', $idCliente, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Reserva');
    }

    public static function ListarReservasA2($fecha = NULL)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT ImporteTotal AS TotalCancelado, ID_Cliente AS Cliente
            FROM Reserva 
            WHERE (FechaBaja = :fecha OR FechaBaja = CURRENT_DATE - 1)
            AND Estado = 'Cancelado'
            ORDER BY ID_Cliente"

        );
        $consulta->bindValue(':fecha', $fecha, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Reserva');
    }

    public static function ListarReservasB2($idCliente)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * FROM Reserva
            WHERE ID_Cliente = :idCliente 
            AND Estado = 'Cancelado'"
        );
        $consulta->bindValue(':idCliente', $idCliente, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Reserva');
    }

    public static function ListarReservasC2($desde, $hasta)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * FROM Reserva
            WHERE FechaBaja BETWEEN :desde AND :hasta 
            AND Estado = 'Cancelado' 
            ORDER BY FechaBaja;"
        );
        $consulta->bindValue(':desde', $desde, PDO::PARAM_STR);
        $consulta->bindValue(':hasta', $hasta, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Reserva');
    }

    public static function ListarReservasD2($tipoCliente)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * FROM Reserva
            WHERE TipoCliente = :tipoCliente
            AND Estado = 'Cancelado'"
        );
        $consulta->bindValue(':tipoCliente', $tipoCliente, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Reserva');
    }

    public static function ListarReservasE($idCliente)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * FROM Reserva
            WHERE ID_Cliente = :idCliente"
        );
        $consulta->bindValue(':idCliente', $idCliente, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Reserva');
    }

    public static function ListarReservasF($formaPago)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * FROM Reserva
            WHERE Pago = :formaPago"
        );
        $consulta->bindValue(':formaPago', $formaPago, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Reserva');
    }
}
