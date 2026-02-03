document.addEventListener('DOMContentLoaded', function () {
    // Botón de eliminación de caja
    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            const clientId = this.getAttribute('data-id');
            
            // Confirmación con SweetAlert2
            Swal.fire({
                title: '¿Estás seguro?',
                text: '¡No podrás recuperar esta caja después de eliminarla!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6181e7',
                cancelButtonColor: '#cd4646',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Enviar solicitud al servidor
                    fetch('eliminarCaja.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            'idCaja': clientId
                        })
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Error en la respuesta del servidor');
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Procesar respuesta del servidor
                            if (data.success) {
                                Swal.fire({
                                    title: '¡Eliminado!',
                                    text: data.message,
                                    icon: 'success',
                                    confirmButtonColor: '#28a745',
                                    confirmButtonText: 'Aceptar'
                                }).then(() => {
                                    // Remover fila correspondiente a la caja eliminada
                                    document.querySelector(`button[data-id="${clientId}"]`).closest('tr').remove();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: data.message, // Mostrar el mensaje proporcionado por el servidor
                                    icon: 'error',
                                    confirmButtonColor: '#d33',
                                    confirmButtonText: 'Aceptar'
                                });
                            }
                        })
                        .catch(error => {
                            // Manejar errores en la solicitud o procesamiento
                            console.error('Error:', error);
                            Swal.fire({
                                title: 'Error',
                                text: 'Hubo un problema al procesar la solicitud. Por favor, inténtalo de nuevo.',
                                icon: 'error',
                                confirmButtonColor: '#d33',
                                confirmButtonText: 'Aceptar'
                            });
                        });
                }
            });
        });
    });


    // Redirección a la página de creación
    document.querySelectorAll('.add-button').forEach(button => {
        button.addEventListener('click', function () {
            Swal.fire({
                title: '¿Deseas agregar un nuevo cliente?',
                text: 'Serás redirigido a la página para la creación de clientes.',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#6181e7',
                cancelButtonColor: '#cd4646',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'crearCaja.php';
                }
            });
        });
    });

    // Redirección a la página de modificar
    document.querySelectorAll('.edit-button').forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            const clientId = this.getAttribute('data-id');
            Swal.fire({
                title: '¿Deseas modificar la caja seleccionada?',
                text: 'Serás redirigido a la página para la actualización de información de las cajas.',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#6181e7',
                cancelButtonColor: '#cd4646',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'modificarCaja.php?id=' + clientId; 
                }
            });
        });
    });
});
