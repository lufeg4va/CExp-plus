<?php
class usuario {
    private $conn;
    private $table_name = "usuario";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para buscar un usuario por su username (Para el Login con MySQLi)
    public function getUsuarioByUsername($username) {
        // Consulta con marcador de posición '?' estándar en MySQLi
        $query = "SELECT id_usuario, username, contrasena, estado FROM " . $this->table_name . " WHERE username = ? LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            die("Error al preparar la consulta: " . $this->conn->error);
        }
        
        // Limpiar y vincular el parámetro ('s' indica que es tipo string)
        $username = htmlspecialchars(strip_tags($username));
        $stmt->bind_param("s", $username);
        
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        // Retornar el array asociativo del usuario o null si no existe
        return $resultado->fetch_assoc();
    }
}
?>