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

            if (!Array.isArray(data)) {
                modalBody.innerHTML = `<p>${data.error || "Datos inválidos recibidos."}</p>`;
                return;
            }

            // Construir la tabla con los datos obtenidos
            const table = document.createElement("table");
            table.classList.add("table", "table-bordered");
            table.innerHTML = `
                <thead>
                    <tr>
                        <th>Indicador</th>
                        ${data.map(row => `<th>${row.tipo_carta_nombre}</th>`).join('')}
                    </tr>
                </thead>
                <tbody>
                    <tr><td>Total Cartas por Enviar</td>${data.map(row => `<td>${row.total_cartas_por_enviar || 0}</td>`).join('')}</tr>
                    <tr><td>Cartas Recibidas en el Mes</td>${data.map(row => `<td>${row.cartas_recibidas_mes || 0}</td>`).join('')}</tr>
                    <tr><td>Cartas Enviadas en el Mes</td>${data.map(row => `<td>${row.enviadas_mes_actual || 0}</td>`).join('')}</tr>
                    <tr><td>Cartas Enviadas en el Mes Anterior</td>${data.map(row => `<td>${row.enviadas_mes_anterior || 0}</td>`).join('')}</tr>
                    <tr><td>Cartas Enviadas con Retraso</td>${data.map(row => `<td>${row.enviadas_con_retraso || 0}</td>`).join('')}</tr>
                    <tr><td>Porcentaje de Retraso</td>${data.map(row => `<td>${row.porcentaje_retraso || 0}%</td>`).join('')}</tr>
                    <tr><td>Promedio de Cumplimiento</td>${data.map(row => `<td>${row.promedio_cumplimiento_dias_envio || 0}</td>`).join('')}</tr>
                    <tr><td>Por Enviar a la Fecha</td>${data.map(row => `<td>${row.por_enviar_a_fecha || 0}</td>`).join('')}</tr>
                    <tr><td>Por Enviar Fuera de Tiempo</td>${data.map(row => `<td>${row.por_enviar_fuera_tiempo || 0}</td>`).join('')}</tr>
                    <tr><td>Total Promotores/Gestores</td>${data.map(row => `<td>${row.total_promotores_gestores || 0}</td>`).join('')}</tr>
                </tbody>
            `;
            modalBody.appendChild(table);

            const modal = new bootstrap.Modal(document.getElementById('modalDetalles'));
            modal.show();
        })
        .catch(error => {
            console.error("Error al cargar los detalles:", error);
            alert("Hubo un problema al cargar los detalles del informe.");
        });
}

// Función para descargar el PDF
function descargarPDF(mesCompleto) {
    const conversion = convertirMesANumero(mesCompleto);

    if (!conversion) {
        console.error("Mes inválido:", mesCompleto);
        alert("No se reconoce el mes seleccionado.");
        return;
    }

    const { mesNumero, anio } = conversion;
    const mesFormato = `${anio}-${mesNumero}`;

    fetch(`../Ajax/informesMensualesA.php?op=obtenerInformeMensual&mes=${mesFormato}`)
        .then(response => response.json())
        .then(data => {
            if (!Array.isArray(data)) {
                console.error("Datos inválidos recibidos.");
                alert("No se pueden generar los detalles del PDF.");
                return;
            }

            // Crear un nuevo documento PDF
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            doc.setFontSize(14);
            doc.text(`Informe Mensual: ${mesCompleto}`, 10, 10);

            const headers = ["Indicador", ...data.map(row => row.tipo_carta_nombre)];
            const rows = [
                ["Total Cartas por Enviar", ...data.map(row => row.total_cartas_por_enviar || 0)],
                ["Cartas Recibidas en el Mes", ...data.map(row => row.cartas_recibidas_mes || 0)],
                ["Cartas Enviadas en el Mes", ...data.map(row => row.enviadas_mes_actual || 0)],
                ["Cartas Enviadas en el Mes Anterior", ...data.map(row => row.enviadas_mes_anterior || 0)],
                ["Cartas Enviadas con Retraso", ...data.map(row => row.enviadas_con_retraso || 0)],
                ["Porcentaje de Retraso", ...data.map(row => `${row.porcentaje_retraso || 0}%`)],
                ["Promedio de Cumplimiento", ...data.map(row => row.promedio_cumplimiento_dias_envio || 0)],
                ["Por Enviar a la Fecha", ...data.map(row => row.por_enviar_a_fecha || 0)],
                ["Por Enviar Fuera de Tiempo", ...data.map(row => row.por_enviar_fuera_tiempo || 0)],
                ["Total Promotores/Gestores", ...data.map(row => row.total_promotores_gestores || 0)]
            ];

            doc.autoTable({
                head: [headers],
                body: rows,
                startY: 20,
                theme: 'striped'
            });

            // Descargar el PDF
            doc.save(`Informe_Mensual_${mesFormato}.pdf`);
        })
        .catch(error => {
            console.error("Error al cargar los detalles para el PDF:", error);
            alert("Hubo un problema al generar el PDF.");
        });
}

// Cargar los meses disponibles al iniciar la página
function cargarMeses() {
    fetch('../Ajax/informesMensualesA.php?op=obtenerMeses')
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector("#tablaMeses tbody");
            tbody.innerHTML = ""; // Limpiar la tabla

            if (!Array.isArray(data) || data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="3">${data.error || "No hay meses disponibles."}</td></tr>`;
                return;
            }

            data.forEach((mes, index) => {
                const mesNormalizado = mes.mes_nombre.trim(); // Asegurarse de eliminar espacios extras
                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${mesNormalizado}</td>
                    <td>
                        <button class="btn btn-primary" onclick="verDetalles('${mesNormalizado}')">Ver Detalles</button>
                        <button class="btn btn-success" onclick="descargarPDF('${mesNormalizado}')">Descargar PDF</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        })
        .catch(error => {
            console.error("Error al cargar los meses:", error);
            alert("Hubo un problema al cargar los meses.");
        });
}

// Llamar a la función para cargar los meses al cargar la página
window.onload = cargarMeses;
