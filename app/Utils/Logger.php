<?php

class Logger
{
    public static function LogOK($metodo, $usuarioID)
    {
        $operacion = self::TraerUltimaOperacion();
        $hoy = new Datetime();
        $data = array(
            "Fecha" => $hoy->format('d/m/Y-H:i:s'),
            "Metodo" => $metodo,
            "UsuarioID" => $usuarioID,
            "Operacion" => ++$operacion
        );
        $archivo = fopen("LogOK.txt", "a");
        fwrite($archivo, "{$data['Fecha']};{$data['Metodo']};ID:{$data['UsuarioID']};{$data['Operacion']}" . "\n");
        fclose($archivo);
        return true;
    }

    public static function LogTodos($metodo)
    {
        $hoy = new Datetime();
        $data = array(
            "Fecha" => $hoy->format('d/m/Y-H:i:s'),
            "Metodo" => $metodo,
        );

        $archivo = fopen("LogTodos.txt", "a");
        fwrite($archivo, "{$data['Fecha']};{$data['Metodo']}" . "\n");
        fclose($archivo);
        return true;
    }

    private static function TraerUltimaOperacion()
    {
        $archivo = fopen("LogOK.txt", "r");
        $ultimaLinea = '';
        if ($archivo)
        {
            $contenido = fread($archivo, filesize("LogOK.txt"));
            $lineas = explode("\n", $contenido);
            $lineasNoVacias = array_filter($lineas);
            $ultimaLinea = end($lineasNoVacias);
            $ultimaLinea = rtrim($ultimaLinea);
            $valores = explode(';', $ultimaLinea);
            $ultimoValor = end($valores);
            return intval($ultimoValor);
        }
        else
            return 0;
    }
}
