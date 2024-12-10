<?php
// modelo/NiñoModel.php
require '../Config/conexion.php'; // Archivo de conexión a la base de datos

class NiñoModel {
    private $conexion;

    public function __construct() {
        $this->conexion = Fn_getConnect();
    }

    public function insertarNiño($numero_nino, $nombre_completo, $aldea, $fecha_nacimiento, $comunidad, $genero, $estado_patrocinio, $fecha_inscripcion, $socio_local, $nombre_alianza, $nombre_contacto_principal) {
        // Preparar la consulta SQL para insertar los datos
        $query = "INSERT INTO niño (numero_nino, nombre_completo, aldea, fecha_nacimiento, comunidad, genero, estado_patrocinio, fecha_inscripcion, socio_local, nombre_alianza, nombre_contacto_principal) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("issssssssss", $numero_nino, $nombre_completo, $aldea, $fecha_nacimiento, $comunidad, $genero, $estado_patrocinio, $fecha_inscripcion, $socio_local, $nombre_alianza, $nombre_contacto_principal);

        // Ejecutar y verificar si la inserción fue exitosa
        if (!$stmt->execute()) {
            return "Error al insertar datos: " . $stmt->error;
        }

        return "Datos insertados correctamente.";
    }
}
?>
