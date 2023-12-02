<?php

class ValidadorCliente
{
    public static function ValidarDatosIngresados($parametros)
    {
        $nombre = $parametros['Nombre'];
        $apellido = $parametros['Apellido'];
        $tipoDoc = strtolower($parametros['TipoDocumento']);
        $nroDoc = $parametros['NroDocumento'];
        $clave = $parametros['Clave'];
        $email = $parametros['Email'];
        $tel = $parametros['Telefono'];
        $pais = $parametros['Pais'];
        $ciudad = $parametros['Ciudad'];
        $tipo = strtolower($parametros['TipoCliente']);
        $tiposDocumentosPermitidos = ['dni', 'pasaporte', 'cedula'];
        $tiposClientesPermitidos = ['individual', 'corporativo'];

        if (empty($nombre) || empty($apellido) || empty($tipoDoc) || empty($nroDoc) || empty($clave) || empty($email) || empty($tel) || empty($pais) || empty($ciudad) || empty($tipo))
            throw new Exception("Todos los campos son obligatorios");
        if (!is_string($nombre) || !is_string($apellido) || !is_string($tipoDoc) || !is_string($pais) || !is_string($ciudad) || !is_string($tipo))
            throw new Exception("Revise los campos no numericos");
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            throw new Exception("El formato del email no es valido");
        if (strlen($clave) < 8)
            throw new Exception("La contraseña debe tener al menos 8 caracteres");
        if (!is_numeric($tel))
            throw new Exception("El numero de telefono no es valido");
        if (!in_array($tipoDoc, $tiposDocumentosPermitidos))
            throw new Exception("Tipo de documento no valido");
        if (!in_array($tipo, $tiposClientesPermitidos))
            throw new Exception("Tipo de cliente no valido");


        return true;
    }
}
