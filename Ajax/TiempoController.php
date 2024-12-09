<?php
require_once "../Modelo/TiempoModel.php";

class ControladorTiempo {
    // Manejar las solicitudes
    public static function manejarSolicitud($accion, $params = []) {
        switch ($accion) {
            case "obtener":
                $tiempos = ModeloTiempo::obtenerTiempos();
                echo json_encode($tiempos);
                break;

            case "actualizar":
                if (isset($params['id'], $params['tiempo_maximo'])) {
                    $id = $params['id'];
                    $tiempo_maximo = $params['tiempo_maximo'];
                    ModeloTiempo::actualizarTiempo($id, $tiempo_maximo);
                    echo json_encode(["status" => "success", "message" => "Tiempo actualizado correctamente"]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Parámetros inválidos"]);
                }
                break;

            case "obtenerTipos":
                $tipos = ModeloTiempo::obtenerTiposCarta();
                echo json_encode($tipos);
                break;

            default:
                echo json_encode(["status" => "error", "message" => "Acción no válida"]);
                break;
        }
    }
}

// Manejar las solicitudes AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    $accion = $_POST['accion'];
    $params = $_POST;
    unset($params['accion']); // Eliminar la acción de los parámetros para evitar conflicto
    ControladorTiempo::manejarSolicitud($accion, $params);
} else {
    echo json_encode(["status" => "error", "message" => "Método no válido o acción no especificada"]);
}
?>
