// Función para convertir el nombre completo del mes y año al número de mes y año
function convertirMesANumero(mesCompleto) {
    const mesesMap = {
        "enero": "01",
        "febrero": "02",
        "marzo": "03",
        "abril": "04",
        "mayo": "05",
        "junio": "06",
        "julio": "07",
        "agosto": "08",
        "septiembre": "09",
        "octubre": "10",
        "noviembre": "11",
        "diciembre": "12"
    };

    if (!mesCompleto || typeof mesCompleto !== "string") {
        console.error("Formato de mes inválido:", mesCompleto);
        return null;
    }

    // Normalizar el texto: eliminar espacios extras y convertir a minúsculas
    mesCompleto = mesCompleto.trim().toLowerCase();
    const partes = mesCompleto.split(" "); // Divide en ["mes", "año"]
    
    if (partes.length !== 2) {
        console.error("Formato inesperado del mes:", mesCompleto);
        return null;
    }

    const mesNombre = partes[0];
    const anio = partes[1];

    // Validar que el mes existe en el mapa y que el año es numérico
    const mesNumero = mesesMap[mesNombre];
    if (!mesNumero || isNaN(anio) || anio <= 0) {
        console.error("Mes o año inválido:", mesCompleto);
        return null;
    }

    return {
        mesNumero: mesNumero,
        anio: anio
    };
}

// Función para ver detalles del informe
function verDetalles(mesCompleto) {
    const conversion = convertirMesANumero(mesCompleto);

    if (!conversion) {
        console.error("Mes inválido:", mesCompleto);
        alert("No se reconoce el mes seleccionado.");
        return;
    }

    const { mesNumero, anio } = conversion;
    const mesFormato = `${anio}-${mesNumero}`; // Formato esperado: YYYY-MM

    fetch(`../Ajax/informesMensualesA.php?op=obtenerInformeMensual&mes=${mesFormato}`)
        .then(response => response.json())
        .then(data => {
            const modalBody = document.querySelector("#modalDetalles .modal-body");
            modalBody.innerHTML = ""; // Limpiar el contenido del modal

            if (data.error) {
                modalBody.innerHTML = `<p>${data.error}</p>`;
            } else {
                // Mostrar los datos en una tabla
                const table = document.createElement("table");
                table.classList.add("table", "table-bordered");
                table.innerHTML = `
                    <thead>
                        <tr>
                            <th>Indicador</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>Total Cartas por Enviar</td><td>${data.total_cartas_por_enviar || 0}</td></tr>
                        <tr><td>Enviadas del Mes Anterior</td><td>${data.enviadas_mes_anterior || 0}</td></tr>
                        <tr><td>Cartas Recibidas en el Mes</td><td>${data.cartas_recibidas_mes || 0}</td></tr>
                        <tr><td>Enviadas en el Mes Actual</td><td>${data.enviadas_mes_actual || 0}</td></tr>
                        <tr><td>Enviadas con Retraso</td><td>${data.enviadas_con_retraso || 0}</td></tr>
                        <tr><td>Porcentaje de Retraso</td><td>${data.porcentaje_retraso || 0}%</td></tr>
                        <tr><td>Porcentaje Cumplimiento de Días de Envío</td><td>${data.promedio_cumplimiento_dias_envio || 0}%</td></tr>
                        <tr><td>Por Enviar a la Fecha</td><td>${data.por_enviar_a_fecha || 0}</td></tr>
                        <tr><td>Por Enviar Fuera del Tiempo Establecido</td><td>${data.por_enviar_fuera_tiempo || 0}</td></tr>
                        <tr><td>Total Promotores/Gestores</td><td>${data.total_promotores_gestores || 0}</td></tr>
                    </tbody>
                `;
                modalBody.appendChild(table);
            }

            const modal = new bootstrap.Modal(document.getElementById('modalDetalles'));
            modal.show();
        })
        .catch(error => {
            console.error("Error al cargar los detalles:", error);
            alert("Hubo un problema al cargar los detalles del informe.");
        });
}

// Cargar los meses disponibles al iniciar la página
function cargarMeses() {
    fetch('../Ajax/informesMensualesA.php?op=obtenerMeses')
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector("#tablaMeses tbody");
            tbody.innerHTML = ""; // Limpiar la tabla

            if (data.error) {
                tbody.innerHTML = `<tr><td colspan="3">${data.error}</td></tr>`;
            } else if (data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="3">No hay meses disponibles.</td></tr>`;
            } else {
                data.forEach((mes, index) => {
                    const mesNormalizado = mes.mes_nombre.trim(); // Asegurarse de eliminar espacios extras
                    const tr = document.createElement("tr");
                    tr.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${mesNormalizado}</td>
                        <td>
                            <button class="btn btn-primary" onclick="verDetalles('${mesNormalizado}')">Ver Detalles</button>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            }
        })
        .catch(error => {
            console.error("Error al cargar los meses:", error);
            const tbody = document.querySelector("#tablaMeses tbody");
            tbody.innerHTML = `<tr><td colspan="3">Error al cargar los meses.</td></tr>`;
        });
}

// Cargar los meses al iniciar la página
document.addEventListener("DOMContentLoaded", cargarMeses);
