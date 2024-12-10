<?php 
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usu'])) {
    header("Location: login.html");
    exit();
}

// Título de la página
$titulo = "Reportes Semestrales";

// Iniciar un buffer para capturar el contenido de la página
ob_start();
?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Gestión de Reportes Semestrales</h1>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <table id="tablaSemestres" class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Semestre</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<!-- Modal de detalles -->
<div id="modalDetalles" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalDetallesLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles Semestrales</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- La tabla se genera aquí dinámicamente -->
            </div>
        </div>
    </div>
</div>

<?php
// Capturar el contenido de la página y guardarlo en la variable $contenido
$contenido = ob_get_clean();

// Incluir la plantilla layout.php
require "layout.php";
?>
<script src="../Js/informesSemestral.js"></script> 
