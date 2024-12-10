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
            <!-- Formulario para definir tiempos -->
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
                <tbody>
                    <!-- Los datos de la tabla se llenarán dinámicamente con JavaScript -->
                </tbody>
            </table>

            <!-- Modal de agregar/editar tiempo -->
            <div class="modal fade" id="modalTiempo" tabindex="-1" role="dialog" aria-labelledby="modalTiempoLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTiempoLabel">Definir Tiempo de Correspondencia</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="formTiempo">
                                <input type="hidden" name="accion" id="accion" value="agregar">
                                <div class="form-group">
                                    <label for="tipo_correspondencia">Tipo de Correspondencia:</label>
                                    <input type="text" name="tipo_correspondencia" id="tipo_correspondencia" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="tiempo_maximo">Tiempo Máximo (días):</label>
                                    <input type="number" name="tiempo_maximo" id="tiempo_maximo" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<script src="../Js/tiempos.js"></script>

<?php
// Capturar el contenido de la página y guardarlo en la variable $contenido
$contenido = ob_get_clean();

// Incluir la plantilla layout.php
require "layout.php";
?>
