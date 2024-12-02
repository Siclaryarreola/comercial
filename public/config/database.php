<?php
//ruta al documento con los datos de conexión
require_once(__DIR__ . '/config.php');

class Database
 {
    //crea una nueva instancia
    private static $instance = null;
    private $connection;

    private function __construct() 
    {
        //esta creando la conexión mysqli y utiliz los datos de conexión
        $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        // Verificar conexión
        if ($this->connection->connect_error) 
        {
            die("Error de conexión: " . $this->connection->connect_error);
        }
        $this->connection->set_charset("utf8"); // Establece el juego de caracteres a utf8
    }

    //verifica el estatus de la bd , si esta inactiva crea una instancia.
    public static function getInstance() 
    {
        if (!self::$instance) 
        {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }
}
