// JavaScript Document
$(document).ready(function() {
    $("#frm").on('submit', function(e) {
        e.preventDefault(); // Evitar el comportamiento por defecto del formulario
        login(); // Llamar a la función para validar el usuario
    });

    function login() {    
        var formData = new FormData($("#frm")[0]); // Crear objeto FormData con los datos del formulario
        $.ajax({
            url: "../Ajax/login.php?op=validar", // URL del controlador
            data: formData, // Datos a enviar
            type: "POST", // Método HTTP
            contentType: false, // Indicar que no se establecerá el tipo de contenido
            processData: false, // Evitar que jQuery procese los datos
            success: function(datos) {
                console.log("Datos sin procesar:", datos); // Salida de depuración
                var response = datos.trim(); // Obtener la respuesta del servidor
                console.log("Respuesta del servidor:", response); // Salida de depuración
                
                // Manejar la respuesta según el tipo de usuario
                switch (response.toLowerCase()) { // Convertir la respuesta a minúsculas para comparar
                    case 'admin':
                        window.location.href = "../Vista/escritorio.php"; // Redirigir a la página de administrador
                        break;
                    case 'coordinador':
                        window.location.href = "../Vista/escritorio.php"; // Redirigir a la página de coordinador
                        break;
                    case 'correspondencia':
                        window.location.href = "../Vista/escritorio.php"; // Redirigir a la página de gestión de correspondencia
                        break;
                    case 'seguimiento':
                        window.location.href = "../Vista/escritorio.php"; // Redirigir a la página de seguimiento
                        break;
                    case 'actualizar ram':
                        window.location.href = "../Vista/escritorio.php"; // Redirigir a la página para actualizar RAM
                        break;
                    case 'progreso':
                        window.location.href = "../Vista/escritorio.php"; // Redirigir a la página de progreso
                        break;
                    case 'no existe':
                        alert("Credenciales incorrectas o usuario no encontrado."); // Mensaje de error
                        break;
                    default:
                        alert("Respuesta inesperada del servidor: " + response); // Manejo de respuestas no esperadas
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error en la solicitud AJAX:", textStatus, errorThrown); // Salida de depuración
                alert("Hubo un error en el servidor: " + textStatus + " " + errorThrown); // Mensaje de error
            }
        });
    }
});
