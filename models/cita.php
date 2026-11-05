<?php
class cita
{
    private $conn;
    private $table_name = "cita";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Obtener todas las citas uniendo paciente y medico
    public function obtenerCitas()
    {
        $query = "SELECT 
                    c.id_cita,
                    c.id_paciente,
                    c.fecha,
                    c.estado,
                    p.nombre AS paciente_nombre,
                    p.apellido_paterno AS paciente_apellido,
                    m.id_medico,
                    m.nombre AS medico_nombre,
                    m.apellido_paterno AS medico_apellido
                  FROM " . $this->table_name . " c
                  INNER JOIN paciente p ON c.id_paciente = p.id_paciente
                  INNER JOIN medico m ON p.id_medico = m.id_medico
                  ORDER BY c.fecha ASC";

        $resultado = $this->conn->query($query);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    //  Obtener lista de médicos 
    public function obtenerMedicos()
    {
        $query = "SELECT id_medico, nombre, apellido_paterno, especialidad FROM medico ORDER BY nombre ASC";
        $resultado = $this->conn->query($query);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    // Obtener lista de pacientes 
    public function obtenerPacientes()
    {
        $query = "SELECT id_paciente, nombre, apellido_paterno FROM paciente ORDER BY nombre ASC";
        $resultado = $this->conn->query($query);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    //  Crear y Insertar una nueva cita
    public function crearCita($id_paciente, $fecha, $estado)
    {
        $query = "INSERT INTO " . $this->table_name . " (id_paciente, fecha, estado) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iss", $id_paciente, $fecha, $estado);
        return $stmt->execute();
    }

    // Actualizar y  modificar una cita existente por su id_cita
    public function actualizarCita($id_cita, $id_paciente, $fecha, $estado)
    {
        $query = "UPDATE " . $this->table_name . " SET id_paciente = ?, fecha = ?, estado = ? WHERE id_cita = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("issi", $id_paciente, $fecha, $estado, $id_cita);
        return $stmt->execute();
    }

    //  Borrar una cita por su id_cita
    public function eliminarCita($id_cita)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_cita = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_cita);
        return $stmt->execute();
    }
}
