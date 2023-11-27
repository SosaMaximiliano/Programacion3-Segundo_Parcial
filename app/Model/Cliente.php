<?php

class Cliente
{
    public static function AltaCliente($nombre, $apellido, $tipoDoc, $nroDoc, $clave, $email, $tel, $pais, $ciudad, $tipoCliente)
    {
        $fechaAlta = new DateTime();
        $fechaBaja = NULL;
        $estado = "Activo";
        $pass = password_hash($clave, PASSWORD_DEFAULT);
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "INSERT INTO Cliente (Nombre,Apellido,TipoDocumento,NroDocumento,Clave,Estado,Alta,Baja,Email,Telefono,Pais,Ciudad,TipoCliente) 
            VALUES (:nombre,:apellido,:tipoDoc,:nroDoc,:clave,:estado,:alta,:baja,:email,:tel,:pais,:ciudad,:tipoCliente)"
        );
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $apellido, PDO::PARAM_STR);
        $consulta->bindValue(':tipoDoc', $tipoDoc, PDO::PARAM_STR);
        $consulta->bindValue(':nroDoc', $nroDoc, PDO::PARAM_STR);
        $consulta->bindValue(':email', $email, PDO::PARAM_STR);
        $consulta->bindValue(':tel', $tel, PDO::PARAM_INT);
        $consulta->bindValue(':pais', $pais, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $pass, PDO::PARAM_STR);
        $consulta->bindValue(':ciudad', $ciudad, PDO::PARAM_STR);
        $consulta->bindValue(':tipoCliente', $tipoCliente, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->bindValue(':alta', $fechaAlta->format('Y-m-d,H:i:s'), PDO::PARAM_STR);
        $consulta->bindValue(':baja', $fechaBaja, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function BajaCliente($idCliente, $tipoCliente)
    {
        $fechaBaja = new DateTime();
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "UPDATE Cliente SET Estado = 'Inactivo', Baja = :fechaBaja
            WHERE ID = :idCliente AND TipoCliente = :tipoCliente"
        );
        $consulta->bindValue(':fechaBaja', $fechaBaja->format('Y-m-d,H:i:s'), PDO::PARAM_STR);
        $consulta->bindValue(':tipoCliente', $tipoCliente, PDO::PARAM_STR);
        $consulta->bindValue(':idCliente', $idCliente, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function ModificarCliente($idCliente, $nombre, $apellido, $tipoDoc, $nroDoc, $email, $tel, $pais, $ciudad, $tipoCliente)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "UPDATE Cliente SET 
            Nombre = :nombre, 
            Apellido = :apellido, 
            TipoDocumento = :tipoDoc,
            NroDocumento = :nroDoc,
            Email = :email,
            Telefono = :tel,
            Pais = :pais,
            Ciudad = :ciudad,
            TipoCliente = :tipoCliente
            WHERE ID = :idCliente AND Estado = 'Activo'"
        );
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $apellido, PDO::PARAM_STR);
        $consulta->bindValue(':tipoDoc', $tipoDoc, PDO::PARAM_STR);
        $consulta->bindValue(':nroDoc', $nroDoc, PDO::PARAM_STR);
        $consulta->bindValue(':email', $email, PDO::PARAM_STR);
        $consulta->bindValue(':tel', $tel, PDO::PARAM_INT);
        $consulta->bindValue(':pais', $pais, PDO::PARAM_STR);
        $consulta->bindValue(':ciudad', $ciudad, PDO::PARAM_STR);
        $consulta->bindValue(':tipoCliente', $tipoCliente, PDO::PARAM_STR);
        $consulta->bindValue(':idCliente', $idCliente, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function CargarImagen($idCliente, $tipoCliente, $archivo)
    {
        $nombreArchivo = '';
        if ($tipoCliente === 'Individual')
            $nombreArchivo = "{$idCliente}IN";
        if ($tipoCliente === 'Corporativo')
            $nombreArchivo = "{$idCliente}CO";

        $extension = pathinfo($archivo->getClientFilename(), PATHINFO_EXTENSION);
        $nombreArchivo .= ".{$extension}";
        $url = "./ImagenesDeClientes/{$nombreArchivo}";

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "UPDATE Cliente SET ImagenURL = :imagen WHERE ID = :idCliente"
        );
        $consulta->bindValue(':idCliente', $idCliente, PDO::PARAM_INT);
        $consulta->bindValue(':imagen', $url, PDO::PARAM_STR);
        $consulta->execute();
        $archivo->moveTo($url);
        return $url;
    }

    public static function MoverImagen($idCliente)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT ImagenURL FROM Cliente WHERE ID = :idCliente"
        );
        $consulta->bindValue(':idCliente', $idCliente, PDO::PARAM_INT);
        $consulta->execute();
        $archivo = $consulta->fetchAll(PDO::FETCH_CLASS, 'Cliente');
        $aux = explode('/', $archivo[0]->ImagenURL);
        $nombreArchivo = $aux[2];
        rename($archivo[0]->ImagenURL, "./ImagenesBackupClientes/$nombreArchivo");
    }

    public static function TraeClientePorID($idCliente)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * FROM Cliente WHERE ID = :idCliente"
        );
        $consulta->bindValue(':idCliente', $idCliente, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Cliente');
    }

    public static function ConsultarCliente($idCliente, $tipo)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT Pais, Ciudad, Telefono FROM Cliente WHERE ID = :idCliente AND TipoCliente = :tipo"
        );
        $consulta->bindValue(':idCliente', $idCliente, PDO::PARAM_INT);
        $consulta->bindValue(':tipo', $tipo, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Cliente');
    }

    public static function ListarClientes()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * FROM Cliente WHERE Estado = 'Activo'"
        );
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Cliente');
    }

    public static function ExisteCliente($nombre, $apellido, $tipoDoc, $nroDoc)
    {
        $clientes = self::ListarClientes();
        foreach ($clientes as $e)
        {
            if (
                $e->Nombre == $nombre
                && $e->Apellido == $apellido
                && $e->TipoDocumento == $tipoDoc
                && $e->NroDocumento == $nroDoc
                && $e->Estado == 'Activo'
            )
                return true;
        }
        return false;
    }
}
