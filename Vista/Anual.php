<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usu'])) {
    header("Location: login.html");
    exit();
}

// Asignar el título de la página
$titulo = "Reportes Anuales";

// Iniciar la salida en buffer
ob_start();
?>

<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0"><?php echo $titulo; ?></h1>
    </div>
</div>

<div class="container">
    <table id="tablaAnios" class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Año</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<!-- Modal de detalles (más grande) -->
<!-- Modal de detalles -->
<div id="modalDetalles" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles Anuales</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- La tabla se genera aquí dinámicamente -->
            </div>
        </div>
    </div>
</div>

<?php
// Capturar el contenido generado
$contenido = ob_get_clean();

// Incluir el layout que contiene la estructura general de la página
require "layout.php";
?>

<!-- Estilos específicos para la tabla dentro del modal -->
<style>
.modal-body table {
    width: 100%;
    overflow-x: auto;
    display: block;
}
/* Si necesitas un tamaño aún mayor */
.modal-lg {
    max-width: 45%; /* 90% del ancho de la pantalla */
}

</style>

<!-- Incluir el script de informes anuales -->
<script src="../Js/informesAnual.js"></script>
