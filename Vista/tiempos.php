<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usu'])) {
    header("Location: login.html");
    exit();
}

// Título de la página
$titulo = "Definición de Tiempos";

// Iniciar un buffer para capturar el contenido de la página
ob_start();
?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Definición de Tiempos de Correspondencia</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Botón para abrir el modal -->
        <button class="btn btn-success mb-3" data-toggle="modal" data-target="#modalTiempo">
            <i class="fas fa-clock"></i> Definir Nuevo Tiempo
        </button>

        <!-- Tabla para mostrar los tiempos definidos -->
        <table id="tablaTiempos" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Tipo de Correspondencia</th>
                    <th>Tiempo Máximo (días)</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <!-- Modal para agregar o editar tiempos -->
        <div class="modal fade" id="modalTiempo" tabindex="-1" role="dialog" aria-labelledby="modalTiempoLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTiempoLabel">Definir Nuevo Tiempo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="formTiempo">
                            <input type="hidden" name="accion" id="accion" value="agregar">
                            <input type="hidden" name="id" id="id_tiempo">
                            <div class="form-group">
                                <label for="id_tipo_carta">Tipo de Correspondencia:</label>
                                <select id="id_tipo_carta" name="id_tipo_carta" class="form-control" required>
                                    <option value="">Seleccione un tipo</option>
                                    <!-- Opciones se cargarán dinámicamente con JavaScript -->
                                </select>
                            </div> 
                            <div class="form-group">
                                <label for="tiempo_maximo">Tiempo Máximo (días):</label>
                                <input type="number" name="tiempo_maximo" id="tiempo_maximo" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Scripts -->



<?php
$contenido = ob_get_clean();
require "layout.php";
?>
<script src="../Js/tiempos.js"></script>