$(document).ready(function () {
    let adminChildrenCount = 0;
    
    // Inicializar DataTable
    const tablaAldeas = $('#tabla-aldeas').DataTable({
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
        },
        columns: [
            { data: 'aldea' },
            { data: 'total_ninos' },
            { data: 'ninos_asignados' },
            {
                data: null,
                render: function(data) {
                    return `<button class="btn btn-primary btn-sm ver-ninos" data-aldea="${data.aldea}">
                        <i class="fas fa-users mr-1"></i>Ver Niños
                    </button>`;
                }
            }
        ]
    });

    function mostrarAlerta(mensaje, tipo) {
        const alertHTML = `
            <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
                ${mensaje}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        `;
        $("#alertas").html(alertHTML);
    }

    function actualizarContadores() {
        const seleccionados = $("input[name='ninos[]']:checked:not(:disabled)").length;
        const totalAsignados = adminChildrenCount + seleccionados;
        $("#contador-seleccionados").text(`Seleccionados: ${seleccionados}`);
        $("#contador-actual").text(`Niños a cargo: ${totalAsignados}`);
    }

    function cargarDatos() {
        $.ajax({
            url: "../Ajax/asignarController.php",
            method: "POST",
            data: { accion: "obtener_datos" },
            dataType: "json",
            success: function(response) {
                if (response.gestores) {
                    let gestorOptions = '<option value="">Seleccione un administrador</option>';
                    response.gestores.forEach((gestor) => {
                        gestorOptions += `<option value="${gestor.Cedula}" data-nombre="${gestor.nombre_completo}">${gestor.nombre_completo}</option>`;
                    });
                    $("#id_gestor").html(gestorOptions);
                }

                if (response.aldeas) {
                    tablaAldeas.clear().rows.add(response.aldeas).draw();
                }
            },
            error: function(xhr, status, error) {
                mostrarAlerta('Error al cargar los datos: ' + error, 'danger');
            }
        });
    }

    // Cargar datos iniciales
    cargarDatos();

    // Evento al cambiar el administrador seleccionado
    $("#id_gestor").change(function() {
        const id_gestor = $(this).val();
        if (id_gestor) {
            $.ajax({
                url: "../Ajax/asignarController.php",
                method: "POST",
                data: {
                    accion: "obtener_ninos_asignados",
                    id_gestor: id_gestor
                },
                dataType: "json",
                success: function(response) {
                    adminChildrenCount = response.total_ninos || 0;
                    actualizarContadores();
                },
                error: function(xhr, status, error) {
                    mostrarAlerta('Error al obtener información del administrador: ' + error, 'danger');
                }
            });
        } else {
            adminChildrenCount = 0;
            actualizarContadores();
        }
    });

    // Manejar clic en botón "Ver Niños"
    $('#tabla-aldeas').on('click', '.ver-ninos', function() {
        const aldea = $(this).data('aldea');
        const id_gestor = $("#id_gestor").val();
        const nombre_gestor = $("#id_gestor option:selected").data('nombre');

        if (!id_gestor) {
            mostrarAlerta('Por favor seleccione un administrador primero', 'warning');
            return;
        }

        $('#nombre-aldea').text(aldea);
        $('#admin-selected').text(nombre_gestor);
        
        $.ajax({
            url: "../Ajax/asignarController.php",
            method: "POST",
            data: {
                accion: "obtener_ninos_aldea",
                aldea: aldea
            },
            dataType: "json",
            success: function(response) {
                let html = '';
                response.ninos.forEach(nino => {
                    const disabled = nino.esta_asignado ? 'disabled' : '';
                    const estado = nino.esta_asignado ? 'Asignado' : 'Disponible';
                    
                    html += `
                        <tr>
                            <td>
                                <input type="checkbox" name="ninos[]" value="${nino.numero_nino}" ${disabled}>
                            </td>
                            <td>${nino.nombre_completo}</td>
                            <td>${estado}</td>
                        </tr>
                    `;
                });
                $("#lista-ninos").html(html);
                
                // Actualizar contadores iniciales
                actualizarContadores();

                // Agregar evento para contar checkboxes seleccionados
                $("input[name='ninos[]']").on('change', actualizarContadores);

                $("#modal-ninos").modal('show');
            },
            error: function(xhr, status, error) {
                mostrarAlerta('Error al cargar los niños: ' + error, 'danger');
            }
        });
    });

    // Manejar guardado de asignación
    $("#btn-guardar-asignacion").click(function() {
        const id_gestor = $("#id_gestor").val();
        const ninos_seleccionados = $("input[name='ninos[]']:checked").map(function() {
            return $(this).val();
        }).get();

        if (!ninos_seleccionados.length) {
            mostrarAlerta('Por favor seleccione al menos un niño', 'warning');
            return;
        }

        $.ajax({
            url: "../Ajax/asignarController.php",
            method: "POST",
            data: {
                accion: "asignar_multiple",
                id_gestor: id_gestor,
                ninos: ninos_seleccionados
            },
            dataType: "json",
            success: function(response) {
                if (response.exito) {
                    $("#modal-ninos").modal('hide');
                    mostrarAlerta('Asignación realizada con éxito', 'success');
                    cargarDatos();
                    // Actualizar el contador de niños asignados
                    adminChildrenCount += ninos_seleccionados.length;
                    actualizarContadores();
                } else {
                    mostrarAlerta(response.mensaje || 'Error al realizar la asignación', 'danger');
                }
            },
            error: function(xhr, status, error) {
                mostrarAlerta('Error en el servidor: ' + error, 'danger');
            }
        });
    });
});