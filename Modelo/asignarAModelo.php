<?php
require_once "../Config/conexion.php";

class Gestor {
    public static function obtenerAdministradores() {
        $conexion = Fn_getConnect();
        
        // Llamada al procedimiento almacenado ObtenerAdministradores
        $sql = "CALL ObtenerAdministradores()";
        $resultado = $conexion->query($sql);
        $gestores = [];

        if ($resultado && $resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $gestores[] = $fila;
            }
        }

        $conexion->close();
        return $gestores;
    }
}

