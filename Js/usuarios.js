  // Declara dataTable como variable global
    let dataTable;

    document.addEventListener('DOMContentLoaded', function() {
      // Inicializar DataTable
        dataTable = $('#tablaUsuarios').DataTable({
            responsive: true,
            language: {
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "search": "Buscar:",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                "infoEmpty": "No hay registros disponibles",
                "infoFiltered": "(filtrado de _MAX_ registros totales)",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            }
        });

      // Cargar usuarios al iniciar la página
        cargarUsuarios(dataTable);

      // Cargar roles en el formulario
        cargarRoles();

      // Evento al enviar el formulario
        document.getElementById('formUsuario').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('../Ajax/usuarioA.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                cargarUsuarios(dataTable); // Recarga la tabla de usuarios
                $('#modalUsuario').modal('hide'); // Cierra el modal
            })
            .catch(error => console.error('Error al guardar el usuario:', error));
        });
    });

    function cargarUsuarios() {
      // Verifica si dataTable ha sido inicializado
        if (!dataTable) {
            console.error('DataTable no ha sido inicializado correctamente.');
            return;
        }

        fetch('../Ajax/usuarioA.php?accion=mostrar')
            .then(response => response.json())
            .then(data => {
                dataTable.clear(); // Limpia la tabla antes de llenarla

                if (data.length > 0) {
                    data.forEach(usuario => {
                            const row = [
                                usuario.Cedula,
                                usuario.nombre_completo,
                                usuario.correo_electronico,
                                usuario.tipo_rol,
                                    `<button class="btn btn-warning" onclick="editarUsuario(${usuario.Cedula})">
                                    <i class="fas fa-edit"></i> Editar
                                    </button>
                                    <button class="btn btn-danger" onclick="eliminarUsuario(${usuario.Cedula})">
                                    <i class="fas fa-trash"></i> Eliminar
                                    </button>`
                                ];
        dataTable.row.add(row);
    });

    } else {
            dataTable.row.add(['No se encontraron usuarios.', '', '', '', '']).draw();
                }

              dataTable.draw(); // Redibuja el DataTable para mostrar los nuevos datos
        })
        .catch(error => console.error('Error al cargar los usuarios:', error));
}

    function cargarRoles() {
        fetch('../Ajax/usuarioA.php?accion=obtenerRoles')
            .then(response => response.json())
            .then(roles => {
                const selectRol = document.getElementById('id_rol');
                selectRol.innerHTML = ''; // Limpia el select

                roles.forEach(rol => {
                    const option = document.createElement('option');
                    option.value = rol.id;
                    option.textContent = rol.tipo_rol;
                    selectRol.appendChild(option);
                });
            })
            .catch(error => console.error('Error al cargar los roles:', error));
    }

    function mostrarFormularioAgregar() {
        document.getElementById('accion').value = 'insertar';
        document.getElementById('formUsuario').reset();
        cargarRoles(); // Carga los roles al mostrar el formulario
    }

    function editarUsuario(cedula) {
    fetch(`../Ajax/usuarioA.php?accion=obtenerPorCedula&cedula=${cedula}`)
        .then(response => response.json())
        .then(usuario => {
            if (usuario) {
                document.getElementById('accion').value = 'actualizar';
                document.getElementById('cedula').value = usuario.Cedula;
                document.getElementById('nombre_completo').value = usuario.nombre_completo;
                document.getElementById('correo_electronico').value = usuario.correo_electronico;
                document.getElementById('id_rol').value = usuario.id_rol;
                document.getElementById('contrasena').value = ''; // Resetea la contraseña
                $('#modalUsuario').modal('show'); // Muestra el modal
            }
        })
        .catch(error => {
            console.error('Error al cargar los datos del usuario:', error);
        });
}

    function eliminarUsuario(cedula) {
        if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
            fetch(`../Ajax/usuarioA.php?accion=eliminar&cedula=${cedula}`, {
                method: 'GET',
            })
            .then(response => response.text())
            .then(data => {
              alert(data); // Mostrar el mensaje de éxito o error
              cargarUsuarios(); // Recargar la tabla de usuarios
            })
            .catch(error => {
                console.error('Error al eliminar el usuario:', error);
            });
        }
    }
