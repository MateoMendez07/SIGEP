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

<!-- Tabla para mostrar los gestores y el botón de acción -->
<table id="tablaNotificaciones" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Gestor</th>
            <th>Acción</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<!-- Incluir jQuery y Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<!-- Incluir DataTables CSS y JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<!-- Tu archivo JS personalizado -->
<script src="../Js/notificaciones.js"></script>

<script>
$(document).ready(function() {
    $('#tablaNotificaciones').DataTable();
});
</script>

<?php
$contenido = ob_get_clean();
require "layout.php";
?>
