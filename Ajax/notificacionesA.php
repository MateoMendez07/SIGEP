<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once '../config/conexion.php';
require_once '../Modelo/notificacionesM.php';

class NotificacionesController {
    private $model;

    public function __construct($db) {
        $this->model = new notificaciones($db);
    }

    // Método para cargar las notificaciones
    public function cargarNotificaciones() {
        $notificaciones = $this->model->obtenerNotificaciones();
        include '../Vista/Notificaciones.php';  // Pasamos las notificaciones a la vista
    }
}
?>
