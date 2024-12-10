<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usu'])) {
    header("Location: login.html");
    exit();
}

// Título de la página
$titulo = "Usuarios";

// Iniciar un buffer para capturar el contenido de la página
ob_start();
?>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Gestión de usuarios</h1>
                </div>
            </div>
        </div>
    </div>
    
    <section class="content">
        <div class="container-fluid">
            <!-- Botón para agregar nuevo usuario -->
            <button class="btn btn-success mb-3" data-toggle="modal" data-target="#modalUsuario" onclick="mostrarFormularioAgregar()">
    			<i class="fas fa-plus"></i> Agregar Usuario
			</button>

            
            <!-- Tabla para mostrar los usuarios -->
            <table id="tablaUsuarios" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Cédula</th>
                        <th>Nombre Completo</th>
                        <th>Correo Electrónico</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Los datos de la tabla se llenarán dinámicamente con JavaScript -->
                </tbody>
            </table>

            <!-- Modal de agregar/editar -->
            <div class="modal fade" id="modalUsuario" tabindex="-1" role="dialog" aria-labelledby="modalUsuarioLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalUsuarioLabel">Formulario de Usuario</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="formUsuario">
                                <input type="hidden" name="accion" id="accion" value="agregar">
                                <div class="form-group">
                                    <label for="cedula">Cédula:</label>
                                    <input type="text" name="cedula" id="cedula" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="nombre_completo">Nombre Completo:</label>
                                    <input type="text" name="nombre_completo" id="nombre_completo" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="correo_electronico">Correo Electrónico:</label>
                                    <input type="email" name="correo_electronico" id="correo_electronico" class="form-control" required>
                                </div>
                                <div class="form-group">
   							 		<label for="id_rol">Rol:</label>
    								<select name="id_rol" id="id_rol" class="form-control" required>
        							<!-- Las opciones se cargarán dinámicamente -->
    								</select>
								</div>
                                <div class="form-group">
                                    <label for="contrasena">Contraseña:</label>
                                    <input type="password" name="contrasena" id="contrasena" class="form-control" required>
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
<script src="../Js/usuarios.js"></script>

<?php
// Capturar el contenido de la página y guardarlo en la variable $contenido
$contenido = ob_get_clean();

// Incluir la plantilla layout.php
require "layout.php";
?>
