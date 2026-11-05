<?php
class grupo {
    private $conn;
    private $table_name = "grupo";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para buscar un grupo por su nombre  
    public function getgrupoByNombre($nombre) {
        // Consulta con marcador de posición '?' estándar en MySQLi
        $query = "SELECT id_grupo, nombre FROM " . $this->table_name . " WHERE nombre = ? LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            die("Error al preparar la consulta: " . $this->conn->error);
        }
        
        // Limpiar y vincular el parámetro 
        $nombre = htmlspecialchars(strip_tags($nombre));
        $stmt->bind_param("s", $nombre);
        
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        // Retornar el array asociativo del grupo o null si no existe
        return $resultado->fetch_assoc();
    }
    public function getgrupoById($id){
        $query = "SELECT id_grupo, nombre FROM " . $this->table_name . " WHERE id_grupo = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            die("Error al preparar la consulta: " . $this->conn->error);
        }
        
        // Limpiar y vincular el parámetro 
        $id = htmlspecialchars(strip_tags($id));
        $stmt->bind_param("i", $id);
        
        $stmt->execute();
        $resultadoid = $stmt->get_result();
        
        // Retornar el array asociativo del grupo o null si no existe
        return $resultadoid->fetch_assoc();
    }

    }
?>