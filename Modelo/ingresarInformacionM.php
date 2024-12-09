<?php
// modelo/NiñoModel.php
require '../Config/conexion.php'; // Archivo de conexión a la base de datos

class NiñoModel
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = Fn_getConnect();
        if (!$this->conexion) {
            throw new Exception("Error al conectar con la base de datos.");
        }
    }

    /**
     * Inserta un nuevo niño usando el procedimiento almacenado `GestionarNiños`.
     */
    public function insertarNiño($numero_nino, $nombre_completo, $aldea, $fecha_nacimiento, $comunidad, $genero, $estado_patrocinio, $fecha_inscripcion, $socio_local, $nombre_alianza, $nombre_contacto_principal, $mesSeleccionado)
    {
        $query = "CALL GestionarNiños('insertar', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($query);

        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $this->conexion->error);
        }

        $stmt->bind_param(
            "isssssssssss",
            $numero_nino,
            $nombre_completo,
            $aldea,
            $fecha_nacimiento,
            $comunidad,
            $genero,
            $estado_patrocinio,
            $fecha_inscripcion,
            $socio_local,
            $nombre_alianza,
            $nombre_contacto_principal,
            $mesSeleccionado
        );

        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar el procedimiento almacenado: " . $stmt->error);
        }

        return "Datos insertados correctamente.";
    }

    /**
     * Actualiza los datos de un niño existente usando el procedimiento almacenado `GestionarNiños`.
     */
    public function actualizarNiño($numero_nino, $nombre_completo, $aldea, $fecha_nacimiento, $comunidad, $genero, $estado_patrocinio, $fecha_inscripcion)
    {
        $query = "CALL GestionarNiños('actualizar', ?, ?, ?, ?, ?, ?, ?, ?, NULL, NULL, NULL, NULL)";
        $stmt = $this->conexion->prepare($query);

        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $this->conexion->error);
        }

        $stmt->bind_param(
            "isssssss",
            $numero_nino,
            $nombre_completo,
            $aldea,
            $fecha_nacimiento,
            $comunidad,
            $genero,
            $estado_patrocinio,
            $fecha_inscripcion
        );

        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar el procedimiento almacenado: " . $stmt->error);
        }

        return "Datos actualizados correctamente.";
    }

    /**
     * Obtiene todos los niños usando el procedimiento almacenado `GestionarNiños`.
     */
    public function obtenerNiños()
    {
        $query = "CALL GestionarNiños('obtener', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)";
        $result = $this->conexion->query($query);

        if (!$result) {
            throw new Exception("Error al ejecutar el procedimiento almacenado: " . $this->conexion->error);
        }

        $ninos = [];
        while ($row = $result->fetch_assoc()) {
            $ninos[] = $row;
        }

        return $ninos;
    }

    /**
     * Obtiene los meses disponibles usando el procedimiento almacenado `GestionarNiños`.
     */
    public function obtenerMesesDisponibles()
    {
        $query = "CALL GestionarNiños('obtenerMesesDisponibles', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)";
        $result = $this->conexion->query($query);

        if (!$result) {
            throw new Exception("Error al ejecutar el procedimiento almacenado: " . $this->conexion->error);
        }

        $meses = [];
        while ($row = $result->fetch_assoc()) {
            $meses[] = $row;
        }

        return $meses;
    }

    /**
     * Obtiene niños registrados en un mes específico usando el procedimiento almacenado `GestionarNiños`.
     */
    public function obtenerNiñosPorMes($mesSeleccionado)
    {
        $query = "CALL GestionarNiños('obtenerPorMes', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ?)";
        $stmt = $this->conexion->prepare($query);

        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $this->conexion->error);
        }

        $stmt->bind_param("s", $mesSeleccionado);

        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar el procedimiento almacenado: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $ninos = [];
        while ($row = $result->fetch_assoc()) {
            $ninos[] = $row;
        }

        return $ninos;
    }
}
