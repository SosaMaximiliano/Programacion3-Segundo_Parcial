<?php
class AccesoDatos
{
    private static $objAccesoDatos;
    private $objetoPDO;

    private function __construct()
    {
        $dsn = 'mysql:host=localhost;dbname=SEGUNDO_PARCIAL;charset=utf8';
        $user = 'root';
        $pass = '';
        try
        {
            $this->objetoPDO = new PDO($dsn, $user, $pass, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            $this->objetoPDO->exec("SET CHARACTER SET utf8");
        }
        catch (PDOException $e)
        {
            print "Error: " . $e->getMessage();
            die();
        }
    }

    public static function obtenerInstancia()
    {
        if (!isset(self::$objAccesoDatos))
        {
            self::$objAccesoDatos = new AccesoDatos();
        }
        return self::$objAccesoDatos;
    }

    public function prepararConsulta($sql)
    {
        return $this->objetoPDO->prepare($sql);
    }

    public function obtenerUltimoId()
    {
        return $this->objetoPDO->lastInsertId();
    }

    public function __clone()
    {
        trigger_error('ERROR: La clonación de este objeto no está permitida', E_USER_ERROR);
    }
}
