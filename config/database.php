<?php
require_once 'env.php';
class Database
{
    private $host = DB_HOST;
    private $db_name = DB_NAME; 
    private $username = DB_USER;
    private $password = DB_PASS;
    public $conexion; //conexion activa
    //metodo 
    public function getConnection()
    {
        $this->conexion = null;

        $this->conexion = new mysqli($this->host, 
        $this->username, $this->password, 
        $this->db_name
        );

        // Verificar si ocurrió algún error de conexión
        if ($this->conexion->connect_error) {
            die("Error de conexión" . $this->conexion->connect_error);
        }

        $this->conexion->set_charset("utf8mb4");

        return $this->conexion;
    }
}