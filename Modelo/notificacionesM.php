<?php
require_once '../Config/conexion.php';

class Notificaciones
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = Fn_getConnect();
    }

    public function obtenerNotificaciones()
    {
        try {
            $query = "
                SELECT 
            c.codigo_mcs, 
            n.nombre_completo AS nombre_nino, 
            c.observaciones, 
            g.nombre_completo AS nombre_gestor
            FROM 
                carta c
            JOIN 
                niño n ON c.numero_nino = n.numero_nino
            JOIN 
                gestor g ON c.id_gestor = g.cedula
            WHERE 
                g.nombre_completo IS NOT NULL AND g.nombre_completo <> ''
            ORDER BY g.nombre_completo, c.codigo_mcs;
            ";

            $stmt = $this->conexion->prepare($query);

            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . $this->conexion->error);
            }

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result) {
                $notificaciones = $result->fetch_all(MYSQLI_ASSOC);
                echo json_encode($notificaciones);
            } else {
                echo json_encode(['error' => 'No se encontraron notificaciones.']);
            }

            $stmt->close();
        } catch (Exception $e) {
            echo json_encode(['error' => 'Error al obtener las notificaciones: ' . $e->getMessage()]);
        }
    }
}
?>
