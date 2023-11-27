<?php


class Utils
{
    public static function LeerJSON($nombreArchivo)
    {
        if (!file_exists("$nombreArchivo.json"))
        {
            echo "El archivo $nombreArchivo no existe";
        }
        try
        {
            $contenido = file_get_contents("$nombreArchivo.json");
            return json_decode($contenido, true);
        }
        catch (Exception $e)
        {
            $e->getMessage();
        }
    }

    public static function EscribirArchivo($jsonData, $nombreArchivo)
    {
        try
        {
            $archivo = fopen("$nombreArchivo.json", "w");
            fwrite($archivo, $jsonData . "\n");
            fclose($archivo);
            return true;
        }
        catch (Exception $e)
        {
            echo "No se pudo abrir el archivo $nombreArchivo para escritura<br>";
            $e->getMessage();
        }
        return false;
    }

    public static function MostrarListadoJSON($nombreArchivo)
    {
        $datos = self::LeerJSON($nombreArchivo);
        foreach ($datos as $e)
        {
            foreach ($e as $key => $value)
            {
                echo "$key : $value <br>";
            }
            echo '<br>';
        }
    }

    public static function UltimoIDJSON($nombreArchivo)
    {
        $listado = self::LeerJSON($nombreArchivo);
        if (!empty($listado))
        {
            $ultimoID = end($listado);
            $idaux = intval($ultimoID["id"] + 1);
        }
        else
            $idaux = rand(300000, 600000);
        return $idaux;
    }
}
