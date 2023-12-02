<?php

class Usuario
{
    public static function AltaUsuario($user, $clave, $rol)
    {
        $fechaAlta = new DateTime();
        $fechaBaja = NULL;
        $estado = "Activo";
        $pass = password_hash($clave, PASSWORD_DEFAULT);
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "INSERT INTO Usuario (User,Pass,Rol,Estado,Alta,Baja) 
            VALUES (:user,:pass,:rol,:estado,:alta,:baja)"
        );
        $consulta->bindValue(':user', $user, PDO::PARAM_STR);
        $consulta->bindValue(':pass', $pass, PDO::PARAM_STR);
        $consulta->bindValue(':rol', $rol, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->bindValue(':alta', $fechaAlta->format('Y-m-d,H:i:s'), PDO::PARAM_STR);
        $consulta->bindValue(':baja', $fechaBaja, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function TraerUsuarioPorUserPass($user, $pass)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT * FROM Usuario WHERE User = :user"
        );
        $consulta->bindValue(':user', $user, PDO::PARAM_STR);
        $consulta->execute();

        $usuario = $consulta->fetchAll(PDO::FETCH_CLASS, 'Cliente');
        if (!empty($usuario) && password_verify($pass, $usuario[0]->Pass))
            return $usuario;
        else
            return NULL;
    }
}
