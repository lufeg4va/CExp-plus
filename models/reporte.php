<?php
class reporte {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Contar total de pacientes registrados
    public function contarPacientes() {
        $query = "SELECT COUNT(*) AS total FROM paciente";
        $resultado = $this->conn->query($query);
        $fila = $resultado->fetch_assoc();
        return (int)($fila['total'] ?? 0);
    }

    // Contar citas pendientes (programadas o confirmadas)
    public function contarCitasPendientes() {
        $query = "SELECT COUNT(*) AS total FROM cita WHERE estado IN ('programada', 'confirmada')";
        $resultado = $this->conn->query($query);
        $fila = $resultado->fetch_assoc();
        return (int)($fila['total'] ?? 0);
    }

    // 3. Contar consultas realizadas
    public function contarConsultas() {
        $query = "SELECT COUNT(*) AS total FROM consulta";
        $resultado = $this->conn->query($query);
        $fila = $resultado->fetch_assoc();
        return (int)($fila['total'] ?? 0);
    }

    // 4. Calcular tasa de asistencia (% de citas que NO fueron canceladas sobre el total)
    public function calcularTasaAsistencia() {
        $query = "SELECT 
                    COUNT(*) AS total,
                    SUM(CASE WHEN estado != 'cancelada' THEN 1 ELSE 0 END) AS asistidas
                  FROM cita";
        $resultado = $this->conn->query($query);
        $fila = $resultado->fetch_assoc();

        $total = (int)($fila['total'] ?? 0);
        if ($total === 0) {
            return 100.0;
        }
        $asistidas = (int)($fila['asistidas'] ?? 0);
        return round(($asistidas / $total) * 100, 1);
    }

    // 5. Obtener las próximas citas para la tarjeta derecha del Dashboard
    public function obtenerProximasCitas($limite = 4) {
        $query = "SELECT 
                    c.id_cita,
                    c.fecha,
                    c.estado,
                    p.nombre AS paciente_nombre,
                    p.apellido_paterno AS paciente_apellido
                  FROM cita c
                  INNER JOIN paciente p ON c.id_paciente = p.id_paciente
                  WHERE c.estado IN ('programada', 'confirmada')
                  ORDER BY c.fecha ASC
                  LIMIT " . (int)$limite;

        $resultado = $this->conn->query($query);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }
}
?>

