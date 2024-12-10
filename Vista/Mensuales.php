<?php
session_start();

if (!isset($_SESSION['usu'])) {
    header("Location: login.html");
    exit();
}

$titulo = "Reportes Mensuales";

ob_start();
?>
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">Gestión de Reportes Mensuales</h1>
    </div>
</div>

<div class="container">
    <h2>Informes Mensuales</h2>
    <table id="tablaMeses" class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Mes</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<div class="modal fade" id="modalDetalles" tabindex="-1" aria-labelledby="modalDetallesLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- El contenido del modal se cargará aquí -->
            </div>
        </div>
    </div>
</div>



<?php
$contenido = ob_get_clean();
require "layout.php";
?>
<script src="../Js/informesMensual.js"></script>