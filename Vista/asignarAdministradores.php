<?php 
session_start();
if (!isset($_SESSION['usu'])) {
    header("Location: login.html");
    exit();
}

$titulo = "Asignar Administradores";
ob_start();
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text">Asignar Niños a Administradores</h1>
            </div>
        </div>
    </div>
</div>
<div id="alertas" class="mt-3"></div>
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label for="id_gestor" class="font-weight-bold">Seleccionar Administrador:</label>
                    <select class="form-control" name="id_gestor" id="id_gestor" required>
                        <option value="" disabled selected>Seleccione un administrador</option>
                    </select>
                </div>

                <div class="table-responsive mt-4">
                    <table id="tabla-aldeas" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Aldea</th>
                                <th>Total Niños</th>
                                <th>Niños Asignados</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal de Niños -->
<div class="modal fade" id="modal-ninos" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title">Niños de la Aldea: <span id="nombre-aldea"></span></h5>
                </div>
                <div class="ml-auto d-flex flex-column align-items-end">
                    <div class="admin-info mb-2">
                        <strong>Administrador:</strong> <span id="admin-selected"></span>
                    </div>
                    <div class="children-stats">
                        <span id="contador-actual" class="mr-3">Niños a cargo: 0</span>
                        <span id="contador-seleccionados">Seleccionados: 0</span>
                    </div>
                </div>
                <button type="button" class="close ml-3" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-asignar-ninos">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Seleccionar</th>
                                    <th>Nombre</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody id="lista-ninos"></tbody>
                        </table>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btn-guardar-asignacion">Guardar Asignación</button>
            </div>
        </div>
    </div>
</div>

<?php 
$contenido = ob_get_clean();
require "layout.php";
?>
<script src="../Js/asignar.js"></script>