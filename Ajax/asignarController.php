<?php
require_once "../Modelo/asignarAModelo.php";
require_once "../Modelo/asignarNiModelo.php";

header('Content-Type: application/json');

function responder($data, $codigo = 200) {
    http_response_code($codigo);
    echo json_encode($data);
    exit;
}

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        responder(['error' => 'Método no permitido'], 405);
    }

    if (!isset($_POST['accion'])) {
        responder(['error' => 'Acción no especificada'], 400);
    }

    switch ($_POST['accion']) {
        case 'obtener_datos':
            $gestores = Gestor::obtenerAdministradores();
            $aldeas = Nino::obtenerResumenAldeas();
            
            if ($gestores === false || $aldeas === false) {
                responder(['error' => 'Error al obtener los datos'], 500);
            }
            
            responder(['gestores' => $gestores, 'aldeas' => $aldeas]);
            break;

        case 'obtener_ninos_aldea':
            if (!isset($_POST['aldea'])) {
                responder(['error' => 'Aldea no especificada'], 400);
            }

            $ninos = Nino::obtenerNinosPorAldea($_POST['aldea']);
            
            if ($ninos === false) {
                responder(['error' => 'Error al obtener los niños'], 500);
            }
            
            responder(['ninos' => $ninos]);
            break;

        case 'obtener_ninos_asignados':
            if (!isset($_POST['id_gestor'])) {
                responder(['error' => 'ID del gestor no especificado'], 400);
            }

            $total = Nino::obtenerNinosAsignados($_POST['id_gestor']);
            
            if ($total === false) {
                responder(['error' => 'Error al obtener el total de niños asignados'], 500);
            }
            
            responder($total);
            break;

        case 'asignar_multiple':
            if (!isset($_POST['id_gestor']) || !isset($_POST['ninos']) || !is_array($_POST['ninos'])) {
                responder(['error' => 'Datos incompletos'], 400);
            }

            $resultado = Nino::asignarMultiplesNinos($_POST['ninos'], $_POST['id_gestor']);
            
            if ($resultado === false) {
                responder(['exito' => false, 'mensaje' => 'Error al realizar la asignación'], 500);
            }

            responder(['exito' => true, 'mensaje' => 'Asignación realizada con éxito']);
            break;

        default:
            responder(['error' => 'Acción no válida'], 400);
    }
} catch (Exception $e) {
    responder(['error' => 'Error interno del servidor', 'mensaje' => $e->getMessage()], 500);
}