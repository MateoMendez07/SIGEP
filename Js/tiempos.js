$(document).ready(function () {
    // Inicializar la página
    cargarTiempos();
    cargarTiposCarta();

    // Función para cargar los tiempos definidos en la tabla
    function cargarTiempos() {
        $.post("../Ajax/TiempoController.php", { accion: "obtener" }, function (data) {
            const tiempos = JSON.parse(data);
            const tbody = $("#tablaTiempos tbody");
            tbody.empty();

            tiempos.forEach((tiempo) => {
                tbody.append(`
                    <tr>
                        <td>${tiempo.tipocarta}</td>
                        <td>${tiempo.tiempo_estimado}</td>
                        <td>
                            <button class="btn btn-warning btnEditar" 
                                    data-id="${tiempo.codigo}" 
                                    data-tiempo="${tiempo.tiempo_estimado}">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                        </td>
                    </tr>
                `);
            });
        }).fail(function () {
            alert("Error al cargar los tiempos.");
        });
    }

    // Función para cargar los tipos de carta en el combobox
    function cargarTiposCarta() {
        $.post("../Ajax/TiempoController.php", { accion: "obtenerTipos" }, function (data) {
            const tipos = JSON.parse(data);
            const select = $("#id_tipo_carta");
            select.empty(); // Limpiar las opciones previas
            select.append('<option value="">Seleccione un tipo</option>');

            tipos.forEach((tipo) => {
                select.append(
                    `<option value="${tipo.codigo}">${tipo.tipocarta}</option>`
                );
            });
        }).fail(function () {
            alert("Error al cargar los tipos de carta.");
        });
    }

    // Manejar el clic en el botón "Editar"
    $(document).on("click", ".btnEditar", function () {
        const id = $(this).data("id");
        const tiempo = $(this).data("tiempo");

        $("#id_tiempo").val(id);
        $("#id_tipo_carta").val(id); // Seleccionar el valor en el combobox
        $("#tiempo_maximo").val(tiempo);
        $("#accion").val("actualizar");
        $("#modalTiempo").modal("show");
    });

    // Manejar el formulario para guardar o actualizar tiempos
    $("#formTiempo").submit(function (e) {
        e.preventDefault();

        const datos = $(this).serialize();

        $.post("../Ajax/TiempoController.php", datos, function (response) {
            const res = JSON.parse(response);

            if (res.status === "success") {
                alert(res.message);
                cargarTiempos();
                $("#modalTiempo").modal("hide");
            } else {
                alert(res.message);
            }
        }).fail(function () {
            alert("Error al guardar el tiempo.");
        });
    });
});
