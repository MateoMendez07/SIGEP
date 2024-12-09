<?php
session_start();
if (!isset($_SESSION['usu'])) {
    header("Location: login.html");
    exit();
}

$titulo = "Notificaciones";

ob_start();
?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Notificaciones</h1>
            </div>
        </div>
    </div>
</div>

<!-- Tabla para mostrar las observaciones -->
<table id="tablaNotificaciones" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Código MCS</th>
            <th>Nombre Completo</th>
            <th>Observaciones</th>
            <th>Gestor</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<!-- Incluyendo jQuery antes de tu archivo JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../Js/notificaciones.js"></script>

<?php
$contenido = ob_get_clean();
require "layout.php";
?>
