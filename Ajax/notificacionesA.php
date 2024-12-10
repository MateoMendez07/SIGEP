<?php
require_once '../Modelo/notificacionesM.php';

$notificaciones = new Notificaciones();
$notificaciones->obtenerNotificaciones();
?>
