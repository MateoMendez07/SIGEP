$(document).ready(function () {
    // Inicializar DataTables con configuraciones personalizadas
    const tablaMeses = $('#tablaMeses').DataTable({
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

    const tablaNiños = $('#tablaNiñosIngresados').DataTable({
        language: {
            
        }
    });

    // Cargar los meses disponibles al iniciar
    cargarMesesDisponibles();

    /**
     * Carga los meses disponibles desde el servidor y los muestra en la tabla.
     */
    function cargarMesesDisponibles() {
        $.ajax({
            url: '../Ajax/obtenerMesesDisponibles.php',
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                tablaMeses.clear();
                response.forEach(mes => {
                    // Convertir la fecha a un objeto Date
                    const fecha = new Date(`${mes.mes_seleccionado}T00:00:00`);
                    
                    // Formatear la fecha en español con mes y año
                    const nombreMes = fecha.toLocaleString('es-ES', {
                        month: 'long',
                        year: 'numeric',
                        timeZone: 'UTC' // Asegurar zona horaria uniforme
                    });
    
                    // Agregar la fila a la tabla
                    tablaMeses.row.add([
                        nombreMes,
                        mes.cantidad,
                        `<button class="btn btn-primary btn-sm ver-ninos" data-mes="${mes.mes_seleccionado}">
                            <i class="fas fa-users mr-1"></i>Ver Niños
                        </button>`
                    ]).draw();
                });
            },
            error: function (xhr, status, error) {
                console.error('Error al cargar los meses disponibles:', error);
                mostrarToast('Error al cargar los meses disponibles', 'error');
            }
        });
    }
    

    /**
     * Maneja el evento de clic para ver los niños de un mes específico.
     */
    $('#tablaMeses').on('click', '.ver-ninos', function () {
        const mes = $(this).data('mes'); // Obtener el mes (YYYY-MM-01)
        
        // Crear un objeto Date con la zona horaria UTC para evitar el desfase horario
        const fecha = new Date(`${mes}T00:00:00Z`);
        
        // Formatear la fecha en español con mes y año
        const nombreMes = fecha.toLocaleString('es-ES', {
            month: 'long',
            year: 'numeric',
            timeZone: 'UTC' // Asegurar zona horaria uniforme para que no haya desfase
        });
    
        $('#mesSeleccionado').text(nombreMes); // Mostrar el mes formateado
        obtenerNinosPorMes(mes); // Llamar a la función para obtener los niños de ese mes
    });
    

    /**
     * Obtiene los niños de un mes específico desde el servidor.
     * @param {string} mes - El mes seleccionado en formato YYYY-MM.
     */
    function obtenerNinosPorMes(mes) {
        $.ajax({
            url: '../Ajax/obtenerNinos.php',
            method: 'GET',
            data: { mes: mes },
            dataType: 'json',
            success: function (response) {
                // Destruir la instancia previa de DataTable si ya existe
                if ($.fn.dataTable.isDataTable('#tablaNiñosIngresados')) {
                    $('#tablaNiñosIngresados').DataTable().clear().destroy();
                }
    
                // Inicializar la tabla de niños con los botones de exportación
                const tablaNiños = $('#tablaNiñosIngresados').DataTable({
                    dom: 'Bfrtip', // El DOM para los botones de exportación
                    buttons: [
                        {
                            extend: 'excel',
                            text: '<i class="fas fa-file-excel"></i> Excel',
                            className: 'btn btn-excel' // Agregar la clase 'btn-verde'
                        },
                        {
                            extend: 'pdf',
                            text: '<i class="fas fa-file-pdf"></i> PDF',
                            className: 'btn btn-pdf' // Agregar la clase 'btn-rojo'
                        }
                    ],
                    language: {
                        // Configura el idioma si lo deseas
                    }
                });
    
                // Limpiar los datos previos y agregar los nuevos
                tablaNiños.clear();
                response.forEach(nino => {
                    tablaNiños.row.add([  
                        nino.numero_nino,
                        nino.nombre_completo,
                        nino.aldea,
                        nino.fecha_nacimiento,
                        nino.comunidad,
                        nino.genero,
                        nino.estado_patrocinio,
                        nino.fecha_inscripcion
                    ]).draw();
                });
    
                // Mostrar el modal con los datos cargados
                $('#modalNinos').modal('show');
            },
            error: function (xhr, status, error) {
                console.error('Error al cargar los niños:', error);
                mostrarToast('Error al cargar los niños', 'error');
            }
        });
    }
    
});
