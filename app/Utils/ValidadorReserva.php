<?php

include_once './Model/Cliente.php';

class ValidadorReserva
{
    public static function ValidarDatosIngresados($parametros)
    {
        $ingreso = $parametros['Ingreso'];
        $salida = $parametros['Salida'];
        $tipoCliente = strtolower($parametros['TipoCliente']);
        $idCliente = $parametros['ID_Cliente'];
        $tipoHabitacion = strtolower($parametros['TipoHabitacion']);
        $importe = $parametros['Importe'];

        $hoy = new DateTime();
        $fechaIngreso = new DateTime($ingreso);
        $fechaSalida = new DateTime($salida);
        $habitacionesValidas = ['simple', 'doble', 'suite'];
        $tipoClienteValido = ['individual', 'corporativo'];
        $cliente = Cliente::TraeClientePorID($idCliente);

        if (empty($ingreso) || empty($salida) || empty($tipoCliente) || empty($idCliente) || empty($tipoHabitacion) || empty($importe))
            throw new Exception("Todos los campos son obligatorios");
        if ($fechaIngreso > $fechaSalida)
            throw new Exception("La fecha de ingreso no puede ser mayor que la fecha de salida");
        if ($fechaIngreso < $hoy)
            throw new Exception("La fecha de ingreso no puede ser menor al dia de la fecha");
        if (!in_array($tipoHabitacion, $habitacionesValidas))
            throw new Exception("Tipo de habitacion no permitido");
        if (!in_array($tipoCliente, $tipoClienteValido))
            throw new Exception("El tipo de cliente no es valido");
        if (empty($cliente))
            throw new Exception("El cliente no existe");
        elseif ($cliente[0]->TipoCliente)
        {
            $tipoAux = '';
            switch ($tipoCliente)
            {
                case 'individual':
                    $tipoAux = 'INDI-' . strtoupper($cliente[0]->TipoDocumento);
                    break;
                case 'corporativo':
                    $tipoAux = 'CORPO-' . strtoupper($cliente[0]->TipoDocumento);
                    break;
            }
            if ($cliente[0]->TipoCliente != $tipoAux)
                throw new Exception("El tipo de cliente no coincide");
            if ($cliente[0]->Estado != 'Activo')
                throw new Exception("El cliente no existe o fue dado de baja");
        }
        if (!is_numeric($importe))
            throw new Exception("El importe debe ser numerico");
        return true;
    }
}
