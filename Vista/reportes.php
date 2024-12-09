<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usu'])) {
    header("Location: login.html");
    exit();
}

// Título de la página
$titulo = "Reportes";

// Iniciar un buffer para capturar el contenido de la página
ob_start();
?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Gestión de Reportes</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Filtros para reportes -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Filtrar Reportes</h3>
            </div>
            <div class="card-body">
                <form id="formReporte">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fecha_inicio">Fecha de Inicio:</label>
                                <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fecha_fin">Fecha de Fin:</label>
                                <input type="date" id="fecha_fin" name="fecha_fin" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tipo_reporte">Tipo de Reporte:</label>
                                <select id="tipo_reporte" name="tipo_reporte" class="form-control">
                                    <option value="correspondencias">Correspondencias</option>
                                    <option value="usuarios">Usuarios</option>
                                    <option value="actividades">Actividades</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="generarReporte()">Generar Reporte</button>
                </form>
            </div>
        </div>

        <!-- Tabla de resultados -->
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">Resultados del Reporte</h3>
            </div>
            <div class="card-body">
                <table id="tablaReportes" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Detalle</th>
                            <th>Fecha</th>
                            <th>Usuario</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Los datos de la tabla se llenarán dinámicamente con JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<script src="../Js/reportes.js"></script>

<?php
// Capturar el contenido de la página y guardarlo en la variable $contenido
$contenido = ob_get_clean();

// Incluir la plantilla layout.php
require "layout.php";
?>
